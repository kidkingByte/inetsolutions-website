<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CoverageArea;
use App\Models\Enquiry;
use App\Models\Package;
use App\Models\Post;
use App\Models\Promotion;
use App\Models\Testimonial;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'connection_requests' => Enquiry::where('type', 'connection_request')->count(),
            'coverage_requests' => Enquiry::whereIn('type', ['coverage_request', 'coverage_notify'])->count(),
            'new_leads' => Enquiry::where('status', 'new')->count(),
            'converted_leads' => Enquiry::where('status', 'converted')->count(),
            'support_tickets' => Enquiry::where('type', 'support_request')->count(),
            'contact_messages' => Enquiry::where('type', 'contact_form')->count(),
            'active_packages' => Package::where('is_published', true)->count(),
            'active_promotions' => Promotion::live()->count(),
            'blog_posts' => Post::count(),
            'testimonials' => Testimonial::count(),
            'coverage_areas' => CoverageArea::count(),
        ];

        $recentEnquiries = Enquiry::latest()->take(10)->get();

        $leadsByType = Enquiry::select('type', DB::raw('count(*) as total'))
            ->groupBy('type')->pluck('total', 'type');

        return view('admin.dashboard', compact('stats', 'recentEnquiries', 'leadsByType'));
    }
}
