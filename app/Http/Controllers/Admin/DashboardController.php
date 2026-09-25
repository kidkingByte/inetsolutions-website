<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CoverageArea;
use App\Models\Enquiry;
use App\Models\Package;
use App\Models\Post;
use App\Models\Promotion;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $visible = fn () => Enquiry::query()->visibleTo($user);
        $leadTypes = array_values(array_diff(array_keys(Enquiry::TYPES), Enquiry::SUPPORT_TYPES));
        $canLeads = $user->can('leads.view');
        $canSupport = $user->can('support.view');

        $kpis = [];
        if ($canLeads) {
            $leads = fn () => Enquiry::query()->whereIn('type', $leadTypes);
            $thisWeek = $leads()->where('created_at', '>=', now()->startOfWeek())->count();
            $lastWeek = $leads()->whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])->count();

            $kpis[] = ['label' => 'New leads', 'value' => $leads()->where('status', 'new')->count(), 'hint' => 'Waiting for first contact', 'icon' => 'users', 'href' => route('admin.enquiries.index', ['status' => 'new'])];
            $kpis[] = ['label' => 'Leads this week', 'value' => $thisWeek, 'hint' => $lastWeek ? sprintf('%+d%% vs last week', round(($thisWeek - $lastWeek) / $lastWeek * 100)) : 'No leads last week', 'icon' => 'chart', 'href' => route('admin.enquiries.index')];
            $kpis[] = ['label' => 'Converted this month', 'value' => $leads()->whereIn('status', ['converted', 'installed'])->where('updated_at', '>=', now()->startOfMonth())->count(), 'hint' => 'Installed or converted', 'icon' => 'check', 'href' => route('admin.enquiries.index', ['status' => 'converted'])];
        }
        if ($canSupport) {
            $kpis[] = ['label' => 'Open support tickets', 'value' => Enquiry::query()->whereIn('type', Enquiry::SUPPORT_TYPES)->open()->count(), 'hint' => 'Not yet resolved', 'icon' => 'support', 'href' => route('admin.enquiries.index', ['type' => 'support_request', 'status' => 'open'])];
        }

        $showEnquiries = $canLeads || $canSupport;

        return view('admin.dashboard', [
            'kpis' => $kpis,
            'showEnquiries' => $showEnquiries,
            // New enquiries nobody has touched for a day, oldest first
            'attention' => $showEnquiries ? $visible()->where('status', 'new')->where('created_at', '<=', now()->subDay())->oldest()->limit(8)->get() : collect(),
            'overdueCount' => $showEnquiries ? $visible()->where('status', 'new')->where('created_at', '<=', now()->subDay())->count() : 0,
            'mine' => $showEnquiries ? $visible()->where('assigned_to', $user->id)->open()->latest('updated_at')->limit(6)->get() : collect(),
            'recent' => $showEnquiries ? $visible()->with('assignee')->latest()->limit(8)->get() : collect(),
            'byType' => $showEnquiries ? $visible()->selectRaw('type, count(*) as total')->groupBy('type')->orderByDesc('total')->pluck('total', 'type') : collect(),
            'byStatus' => $showEnquiries ? $visible()->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status') : collect(),
            'content' => array_filter([
                $user->can('packages.manage') ? ['Active packages', Package::where('is_published', true)->count(), route('admin.packages.index'), 'card'] : null,
                $user->can('coverage.manage') ? ['Coverage areas', CoverageArea::count(), route('admin.coverage.index'), 'map-pin'] : null,
                $user->can('content.manage') ? ['Live promotions', Promotion::live()->count(), route('admin.promotions.index'), 'sparkles'] : null,
                $user->can('content.manage') ? ['Published posts', Post::published()->count(), route('admin.posts.index'), 'document'] : null,
                $user->can('content.manage') ? ['Testimonials', Testimonial::count(), route('admin.testimonials.index'), 'users'] : null,
            ]),
        ]);
    }
}
