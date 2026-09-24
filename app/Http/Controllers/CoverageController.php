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
            'status' => $match['status'],           // available | coming_soon | not_available
            'message' => $match['message'],
            'area' => $match['area'],
            'installation_available' => $match['area']?->installation_available ?? false,
        ];

        if ($request->expectsJson()) {
            return response()->json($result);
        }

        $request->session()->flashInput($request->all());

        return view('site.coverage')->with('result', $result);
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
        $query = CoverageArea::query()->whereRaw('LOWER(region) = ?', [mb_strtolower($data['region'])]);

        if (! empty($data['district'])) {
            $query->whereRaw('LOWER(district) = ?', [mb_strtolower($data['district'])]);
        }
        if (! empty($data['ward'])) {
            $query->whereRaw('LOWER(ward) = ?', [mb_strtolower($data['ward'])]);
        }

        // Prefer the most specific match.
        $area = (clone $query)->latest()->first();

        if (! $area) {
            // Fall back to a region-only match.
            $area = CoverageArea::whereRaw('LOWER(region) = ?', [mb_strtolower($data['region'])])
                ->where(function ($q) use ($data) {
                    if (! empty($data['district'])) {
                        $q->orWhereNull('district');
                    }
                })
                ->latest()->first();
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
