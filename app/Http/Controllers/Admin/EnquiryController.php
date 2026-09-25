<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Enquiry;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EnquiryController extends Controller
{
    public function index(Request $request)
    {
        $types = Enquiry::typesVisibleTo($request->user());
        abort_if($types === [], 403);

        $enquiries = $this->filtered($request)->with('assignee')->latest()->paginate(20)->withQueryString();

        return view('admin.enquiries.index', [
            'enquiries' => $enquiries,
            'types' => array_intersect_key(Enquiry::TYPES, array_flip($types)),
            'statuses' => Enquiry::STATUSES,
            'staff' => User::staff()->where('is_active', true)->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function show(Request $request, Enquiry $enquiry)
    {
        Gate::authorize($enquiry->permission('view'));

        $activity = AuditLog::with('user')->whereMorphedTo('auditable', $enquiry)->latest('created_at')->latest('id')->limit(30)->get();
        // Names for "assignee: A → B" lines, fetched in one query.
        $assigneeIds = $activity->flatMap(fn ($log) => array_values($log->changes['assigned_to'] ?? []))->filter()->unique();

        return view('admin.enquiries.show', [
            'enquiry' => $enquiry->load('assignee'),
            'assignable' => $this->assignableStaff($enquiry),
            'activity' => $activity,
            'userNames' => User::whereIn('id', $assigneeIds)->pluck('name', 'id'),
        ]);
    }

    public function update(Request $request, Enquiry $enquiry)
    {
        Gate::authorize($enquiry->permission('manage'));

        $data = $request->validate([
            'status' => ['sometimes', 'required', Rule::in(Enquiry::STATUSES)],
            'admin_notes' => ['sometimes', 'nullable', 'string', 'max:5000'],
            'assigned_to' => ['sometimes', 'nullable', Rule::in($this->assignableStaff($enquiry)->pluck('id'))],
        ]);

        $enquiry->update($data);

        return back()->with('status', 'Enquiry updated.');
    }

    public function destroy(Enquiry $enquiry)
    {
        Gate::authorize('leads.delete');

        $enquiry->delete();

        return redirect()->route('admin.enquiries.index')->with('status', 'Enquiry deleted.');
    }

    public function attachment(Enquiry $enquiry)
    {
        Gate::authorize($enquiry->permission('view'));
        abort_unless($enquiry->attachment && Storage::disk('local')->exists($enquiry->attachment), 404);

        return Storage::disk('local')->download($enquiry->attachment);
    }

    /** CSV of the current filtered list, for follow-up in Excel / Google Sheets. */
    public function export(Request $request): StreamedResponse
    {
        $query = $this->filtered($request)->with('assignee')->latest();
        $count = (clone $query)->count();

        AuditLog::record('exported', null, "Exported {$count} enquiries to CSV", array_filter($request->only('area', 'type', 'status', 'assigned', 'q')) ?: null);

        return response()->streamDownload(function () use ($query) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['ID', 'Date', 'Type', 'Status', 'Assigned to', 'Name', 'Phone', 'Email', 'Region', 'District', 'Ward', 'Street', 'Address', 'Service', 'Package', 'Problem', 'Message', 'Notes']);

            foreach ($query->lazy(200) as $e) {
                fputcsv($out, array_map([$this, 'csvSafe'], [
                    $e->id, $e->created_at->toDateTimeString(), $e->type_label, $e->status_label, $e->assignee?->name,
                    $e->full_name, $e->phone, $e->email, $e->region, $e->district, $e->ward, $e->street, $e->address,
                    $e->service_required, $e->preferred_package, $e->problem_type, $e->message, $e->admin_notes,
                ]));
            }

            fclose($out);
        }, 'inet-enquiries-'.now()->format('Y-m-d').'.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /** Enquiries the user may see, narrowed by the list filters. */
    protected function filtered(Request $request): Builder
    {
        $user = $request->user();

        return Enquiry::query()
            ->visibleTo($user)
            ->when($request->query('area'), fn ($q, $a) => $a === 'support'
                ? $q->whereIn('type', Enquiry::SUPPORT_TYPES)
                : $q->whereNotIn('type', Enquiry::SUPPORT_TYPES))
            ->when($request->query('type'), fn ($q, $t) => $q->where('type', $t))
            ->when($request->query('status'), fn ($q, $s) => $s === 'open' ? $q->open() : $q->where('status', $s))
            ->when($request->query('assigned'), fn ($q, $a) => match ($a) {
                'me' => $q->where('assigned_to', $user->id),
                'none' => $q->whereNull('assigned_to'),
                default => $q->where('assigned_to', (int) $a),
            })
            ->when($request->query('q'), function ($q, $term) {
                $q->where(function ($x) use ($term) {
                    $x->where('full_name', 'like', "%{$term}%")
                        ->orWhere('phone', 'like', "%{$term}%")
                        ->orWhere('email', 'like', "%{$term}%");
                });
            });
    }

    /** Active staff allowed to work this enquiry (sales for leads, support for tickets). */
    protected function assignableStaff(Enquiry $enquiry)
    {
        return User::staff()->where('is_active', true)->orderBy('name')->get()
            ->filter(fn (User $u) => $u->hasPermission($enquiry->permission('manage')))
            ->values();
    }

    /** Stop spreadsheet apps from executing cell values as formulas (CSV injection). */
    protected function csvSafe($value): string
    {
        $value = (string) $value;

        return $value !== '' && in_array($value[0], ['=', '+', '-', '@', "\t", "\r"], true) ? "'".$value : $value;
    }
}
