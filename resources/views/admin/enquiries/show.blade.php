@extends('layouts.admin')

@section('title', 'Enquiry #' . $enquiry->id)
@section('heading', $enquiry->type_label . ' #' . $enquiry->id)

@php
    $canManage = auth()->user()->can($enquiry->permission('manage'));
    $rows = array_filter([
        'Email' => $enquiry->email,
        'Phone' => $enquiry->phone,
        'Customer ID' => $enquiry->customer_id,
        'Service required' => $enquiry->service_required,
        'Preferred package' => $enquiry->preferred_package,
        'Preferred install date' => $enquiry->preferred_installation_date?->format('d M Y'),
        'Region' => $enquiry->region,
        'District' => $enquiry->district,
        'Ward' => $enquiry->ward,
        'Street' => $enquiry->street,
        'Address' => $enquiry->address,
        'Subject' => $enquiry->subject,
        'Problem type' => $enquiry->problem_type,
        'Source' => $enquiry->source,
        'IP address' => $enquiry->ip_address,
    ], fn ($v) => $v !== null && $v !== '');
    $greeting = rawurlencode('Hello '.($enquiry->full_name ?: '').', this is '.auth()->user()->name.' from INET SOLUTIONS regarding your '.strtolower($enquiry->type_label).'.');
@endphp

@section('content')
    <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('admin.enquiries.index') }}" class="link-arrow !min-h-0">← Back</a>

    <div class="mt-4 grid gap-6 xl:grid-cols-3">
        <div class="space-y-6 xl:col-span-2">
            {{-- Contact header with one-tap follow-up actions --}}
            <section class="card">
                <div class="flex flex-col gap-5 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <h2 class="text-2xl font-extrabold text-ink">{{ $enquiry->full_name ?: 'Unnamed visitor' }}</h2>
                        <p class="mt-1 text-sm text-slate-500">{{ $enquiry->type_label }} · received {{ $enquiry->created_at->format('d M Y, H:i') }} ({{ $enquiry->created_at->diffForHumans() }})</p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        @if($enquiry->phone)
                            <a href="tel:{{ $enquiry->phone }}" class="btn-primary !px-4 !py-2.5"><x-site.icon name="phone" class="h-4 w-4" /> Call</a>
                            <a href="https://wa.me/{{ $enquiry->whatsapp_number }}?text={{ $greeting }}" target="_blank" rel="noopener" class="btn-whatsapp !px-4 !py-2.5"><x-site.icon name="whatsapp" class="h-4 w-4" /> WhatsApp</a>
                        @endif
                        @if($enquiry->email)
                            <a href="mailto:{{ $enquiry->email }}?subject={{ rawurlencode('Your '.$enquiry->type_label.' — INET SOLUTIONS') }}" class="btn-secondary !px-4 !py-2.5"><x-site.icon name="mail" class="h-4 w-4" /> Email</a>
                        @endif
                    </div>
                </div>

                @if ($enquiry->message)
                    <div class="mt-6 rounded-2xl bg-brand-light p-4 text-sm leading-relaxed text-slate-700 whitespace-pre-line">{{ $enquiry->message }}</div>
                @endif

                <dl class="mt-6 grid gap-x-6 gap-y-3 text-sm sm:grid-cols-2">
                    @foreach ($rows as $label => $value)
                        <div class="border-b border-slate-100 pb-2">
                            <dt class="text-xs text-slate-500">{{ $label }}</dt>
                            <dd class="break-words font-medium text-ink">{{ $value }}</dd>
                        </div>
                    @endforeach
                    @if ($enquiry->attachment)
                        <div class="border-b border-slate-100 pb-2">
                            <dt class="text-xs text-slate-500">Attachment</dt>
                            <dd><a class="font-semibold text-brand-700 hover:underline" href="{{ route('admin.enquiries.attachment', $enquiry) }}">Download file</a></dd>
                        </div>
                    @endif
                </dl>
            </section>

            {{-- Activity timeline (from the audit log) --}}
            <section class="card">
                <h2 class="font-bold text-ink">Activity</h2>
                <ol class="mt-5 space-y-5 border-l-2 border-slate-100 pl-5">
                    @foreach ($activity as $log)
                        <li class="relative">
                            <span class="absolute -left-[1.62rem] top-1 h-3 w-3 rounded-full border-2 border-white bg-brand-600 ring-2 ring-brand-100"></span>
                            <p class="text-sm"><span class="font-semibold text-ink">{{ $log->user?->name ?? 'System' }}</span> <span class="text-slate-500">{{ strtolower($log->event_label) }} this enquiry · {{ $log->created_at->diffForHumans() }}</span></p>
                            @if($log->event === 'updated' && $log->changes)
                                <ul class="mt-1 space-y-0.5 text-xs text-slate-600">
                                    @foreach ($log->changes as $field => $change)
                                        <li><span class="font-medium">{{ ucfirst(str_replace('_', ' ', $field === 'assigned_to' ? 'assignee' : $field)) }}:</span>
                                            @if($field === 'assigned_to')
                                                {{ $userNames[$change['old'] ?? 0] ?? 'nobody' }} → {{ $userNames[$change['new'] ?? 0] ?? 'nobody' }}
                                            @elseif($field === 'admin_notes')
                                                notes updated
                                            @else
                                                {{ str_replace('_', ' ', (string) ($change['old'] ?? '—')) }} → {{ str_replace('_', ' ', (string) ($change['new'] ?? '—')) }}
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                    <li class="relative">
                        <span class="absolute -left-[1.62rem] top-1 h-3 w-3 rounded-full border-2 border-white bg-accent-600 ring-2 ring-accent-100"></span>
                        <p class="text-sm"><span class="font-semibold text-ink">Website</span> <span class="text-slate-500">received this enquiry · {{ $enquiry->created_at->diffForHumans() }}</span></p>
                    </li>
                </ol>
            </section>
        </div>

        {{-- Manage --}}
        <aside class="space-y-6">
            <section class="card">
                <h2 class="font-bold text-ink">Manage</h2>
                @if($canManage)
                    <form method="POST" action="{{ route('admin.enquiries.update', $enquiry) }}" class="mt-5 space-y-4">
                        @csrf @method('PUT')
                        <div>
                            <label class="field-label" for="status">Status</label>
                            <select id="status" name="status" class="field">
                                @foreach (\App\Models\Enquiry::STATUSES as $val)
                                    <option value="{{ $val }}" @selected($enquiry->status === $val)>{{ ucfirst(str_replace('_', ' ', $val)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="field-label" for="assigned_to">Assigned to</label>
                            <select id="assigned_to" name="assigned_to" class="field">
                                <option value="">Unassigned</option>
                                @foreach ($assignable as $member)
                                    <option value="{{ $member->id }}" @selected($enquiry->assigned_to === $member->id)>{{ $member->name }}{{ $member->is(auth()->user()) ? ' (me)' : '' }} — {{ $member->role_label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="field-label" for="admin_notes">Internal notes</label>
                            <textarea id="admin_notes" name="admin_notes" rows="6" class="field" placeholder="Calls made, what was agreed, next step…">{{ old('admin_notes', $enquiry->admin_notes) }}</textarea>
                        </div>
                        <button class="btn-primary w-full">Save changes</button>
                    </form>
                @else
                    <dl class="mt-4 space-y-2 text-sm">
                        <div><dt class="text-xs text-slate-500">Status</dt><dd class="font-medium text-ink">{{ $enquiry->status_label }}</dd></div>
                        <div><dt class="text-xs text-slate-500">Assigned to</dt><dd class="font-medium text-ink">{{ $enquiry->assignee?->name ?? 'Unassigned' }}</dd></div>
                    </dl>
                    <p class="mt-4 text-xs text-slate-500">Your role can view this enquiry but not change it.</p>
                @endif
            </section>

            @can('leads.delete')
                <form method="POST" action="{{ route('admin.enquiries.destroy', $enquiry) }}" onsubmit="return confirm('Delete this enquiry? It will be moved to the bin and logged.')">
                    @csrf @method('DELETE')
                    <button class="btn w-full border border-red-200 bg-white text-red-700 hover:bg-red-50">Delete enquiry</button>
                </form>
            @endcan
        </aside>
    </div>
@endsection
