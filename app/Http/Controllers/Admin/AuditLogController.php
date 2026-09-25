<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = AuditLog::with('user')
            ->when($request->query('event'), fn ($q, $e) => $q->where('event', $e))
            ->when($request->query('user'), fn ($q, $u) => $q->where('user_id', (int) $u))
            ->when($request->query('type'), fn ($q, $t) => $q->where('auditable_type', 'App\\Models\\'.class_basename($t)))
            ->latest('created_at')->latest('id')
            ->paginate(30)->withQueryString();

        return view('admin.audit.index', [
            'logs' => $logs,
            'events' => AuditLog::EVENTS,
            'users' => User::staff()->orderBy('name')->get(['id', 'name']),
            'types' => AuditLog::query()->whereNotNull('auditable_type')->distinct()->pluck('auditable_type')
                ->map(fn ($t) => class_basename($t))->sort()->values(),
        ]);
    }
}
