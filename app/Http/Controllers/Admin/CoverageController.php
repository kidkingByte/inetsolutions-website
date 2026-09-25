<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CoverageArea;
use Illuminate\Http\Request;

class CoverageController extends Controller
{
    public function index(Request $request)
    {
        $areas = CoverageArea::query()
            ->when($request->query('q'), function ($q, $term) {
                $q->where(function ($x) use ($term) {
                    $x->where('region', 'like', "%{$term}%")
                        ->orWhere('district', 'like', "%{$term}%")
                        ->orWhere('ward', 'like', "%{$term}%");
                });
            })
            ->orderBy('region')->orderBy('district')->paginate(20);

        return view('admin.coverage.index', compact('areas'));
    }

    public function create()
    {
        return view('admin.coverage.form', ['area' => new CoverageArea]);
    }

    public function store(Request $request)
    {
        CoverageArea::create($this->validated($request));

        return redirect()->route('admin.coverage.index')->with('status', 'Coverage area added.');
    }

    public function edit(CoverageArea $coverage)
    {
        return view('admin.coverage.form', ['area' => $coverage]);
    }

    public function update(Request $request, CoverageArea $coverage)
    {
        $coverage->update($this->validated($request));

        return redirect()->route('admin.coverage.index')->with('status', 'Coverage area updated.');
    }

    public function destroy(CoverageArea $coverage)
    {
        $coverage->delete();

        return back()->with('status', 'Coverage area deleted.');
    }

    protected function validated(Request $request): array
    {
        $data = $request->validate([
            'region' => ['required', 'string', 'max:120'],
            'district' => ['nullable', 'string', 'max:120'],
            'ward' => ['nullable', 'string', 'max:120'],
            'street' => ['nullable', 'string', 'max:120'],
            'service_type' => ['nullable', 'string', 'max:60'],
            'technology' => ['nullable', 'string', 'max:60'],
            'status' => ['required', 'in:available,coming_soon,under_expansion,not_available'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $data['region'] = CoverageArea::canonicalRegion($data['region']);
        $data['installation_available'] = $request->boolean('installation_available');

        return $data;
    }
}
