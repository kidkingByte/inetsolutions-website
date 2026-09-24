<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index(Request $request)
    {
        $packages = Package::query()
            ->when($request->query('category'), fn ($q, $c) => $q->where('category', $c))
            ->orderBy('sort_order')->orderBy('id')->paginate(15);

        return view('admin.packages.index', compact('packages'));
    }

    public function create()
    {
        return view('admin.packages.form', ['package' => new Package]);
    }

    public function store(Request $request)
    {
        Package::create($this->validated($request));

        return redirect()->route('admin.packages.index')->with('status', 'Package created.');
    }

    public function edit(Package $package)
    {
        return view('admin.packages.form', compact('package'));
    }

    public function update(Request $request, Package $package)
    {
        $package->update($this->validated($request));

        return redirect()->route('admin.packages.index')->with('status', 'Package updated.');
    }

    public function destroy(Package $package)
    {
        $package->delete();

        return back()->with('status', 'Package deleted.');
    }

    protected function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'category' => ['required', 'in:home,business,enterprise'],
            'speed' => ['nullable', 'string', 'max:60'],
            'download_speed' => ['nullable', 'string', 'max:60'],
            'upload_speed' => ['nullable', 'string', 'max:60'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'installation_fee' => ['nullable', 'numeric', 'min:0'],
            'validity' => ['nullable', 'string', 'max:60'],
            'recommended_users' => ['nullable', 'integer', 'min:1'],
            'router_info' => ['nullable', 'string', 'max:160'],
            'fair_usage_policy' => ['nullable', 'string', 'max:1000'],
            'installation_time' => ['nullable', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:500'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_featured' => ['boolean'],
            'is_published' => ['boolean'],
        ]);

        $data['features'] = array_values(array_filter(
            (array) $request->input('features', [])
        ));

        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_published'] = $request->boolean('is_published');

        return $data;
    }
}
