<?php

namespace App\Http\Controllers;

use App\Models\CoverageArea;
use App\Models\Enquiry;
use Illuminate\Http\Request;

class CoverageController extends Controller
{
    public function index()
    {
        return view('site.coverage');
    }

    public function check(Request $request)
    {
        $request->merge(['region' => CoverageArea::canonicalRegion((string) $request->input('region'))]);

        $data = $request->validate([
            'full_name' => ['nullable', 'string', 'max:120'],
            'phone' => ['nullable', 'string', 'max:30'],
            'region' => ['required', 'string', 'max:120'],
            'district' => ['nullable', 'string', 'max:120'],
            'ward' => ['nullable', 'string', 'max:120'],
            'street' => ['nullable', 'string', 'max:120'],
            'service_required' => ['nullable', 'string', 'max:60'],
        ]);

        $match = $this->matchCoverage($data);

        // Capture the enquiry as a lead.
        Enquiry::create([
            'type' => 'coverage_request',
            'full_name' => $data['full_name'] ?? null,
            'phone' => $data['phone'] ?? null,
            'region' => $data['region'],
            'district' => $data['district'] ?? null,
            'ward' => $data['ward'] ?? null,
            'street' => $data['street'] ?? null,
            'service_required' => $data['service_required'] ?? null,
            'message' => 'Coverage check: '.($match['status']),
            'source' => $request->path(),
            'ip_address' => $request->ip(),
        ]);

        $result = [
            'status' => $match['status'],           // available | partial | coming_soon | not_available
            'message' => $match['message'],
            'area' => $match['area'],
            'installation_available' => $match['area']?->installation_available ?? false,
        ];

        if ($request->expectsJson()) {
            // `html` lets the coverage page show the result in place without a reload.
            return response()->json($result + [
                'html' => view('site.partials.coverage-result', ['result' => $result, 'input' => $data])->render(),
            ]);
        }

        $request->session()->flashInput($request->all());

        return view('site.coverage', ['result' => $result, 'input' => $data]);
    }

    public function notify(Request $request)
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:120'],
            'region' => ['required', 'string', 'max:120'],
            'district' => ['nullable', 'string', 'max:120'],
            'ward' => ['nullable', 'string', 'max:120'],
        ]);

        Enquiry::create($data + [
            'type' => 'coverage_notify',
            'source' => $request->path(),
            'ip_address' => $request->ip(),
        ]);

        return back()->with('status', 'Thanks! We will notify you when INET is available in your area.');
    }

    protected function matchCoverage(array $data): array
    {
        $inRegion = fn () => CoverageArea::query()->whereRaw('LOWER(region) = ?', [mb_strtolower($data['region'])]);
        $district = mb_strtolower($data['district'] ?? '');
        $ward = mb_strtolower($data['ward'] ?? '');

        // Most specific first. A broader lookup only answers when every area it covers
        // agrees; otherwise one covered ward would wrongly report a whole region as available.
        $candidates = array_filter([
            $district && $ward ? fn () => $inRegion()->whereRaw('LOWER(district) = ?', [$district])->whereRaw('LOWER(ward) = ?', [$ward])->get() : null,
            $district ? fn () => $inRegion()->whereRaw('LOWER(district) = ?', [$district])->whereNull('ward')->get() : null,
            $district ? fn () => $inRegion()->whereRaw('LOWER(district) = ?', [$district])->get() : null,
            fn () => $inRegion()->whereNull('district')->get(),
            fn () => $inRegion()->get(),
        ]);

        $area = null;
        foreach ($candidates as $lookup) {
            $areas = $lookup();
            if ($areas->isEmpty()) {
                continue;
            }
            if ($areas->pluck('status')->unique()->count() > 1) {
                return [
                    'status' => 'partial',
                    'message' => 'INET is available in parts of this area. Add your district and ward for an exact answer, or request a connection and our team will confirm.',
                    'area' => null,
                ];
            }
            $area = $areas->sortByDesc('created_at')->first();
            break;
        }

        if (! $area) {
            return [
                'status' => 'not_available',
                'message' => 'We currently do not provide service in this area.',
                'area' => null,
            ];
        }

        return match ($area->status) {
            'available' => [
                'status' => 'available',
                'message' => 'INET is available in your area.',
                'area' => $area,
            ],
            'coming_soon', 'under_expansion' => [
                'status' => 'coming_soon',
                'message' => 'INET service is coming soon to your area.',
                'area' => $area,
            ],
            default => [
                'status' => 'not_available',
                'message' => 'We currently do not provide service in this area.',
                'area' => $area,
            ],
        };
    }
}
