<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NetworkStatus;
use Illuminate\Http\Request;

class NetworkStatusController extends Controller
{
    public function index()
    {
        return view('admin.network-status.index', ['statuses' => NetworkStatus::ordered()->get()]);
    }

    public function store(Request $request)
    {
        NetworkStatus::create($this->validated($request));

        return back()->with('status', 'Service added.');
    }

    public function update(Request $request, NetworkStatus $network_status)
    {
        $network_status->update($this->validated($request));

        return back()->with('status', 'Status updated.');
    }

    public function destroy(NetworkStatus $network_status)
    {
        $network_status->delete();

        return back()->with('status', 'Service removed.');
    }

    protected function validated(Request $request): array
    {
        return $request->validate([
            'service' => ['required', 'string', 'max:120'],
            'status' => ['required', 'in:operational,degraded,outage,maintenance'],
            'message' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);
    }
}
