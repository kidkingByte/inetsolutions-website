<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\NetworkStatus;
use App\Models\Package;
use App\Models\Post;
use App\Models\Promotion;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function home()
    {
        return view('site.home', [
            'homePackages' => Package::published()->category('home')->ordered()->take(3)->get(),
            'featured' => Package::published()->where('is_featured', true)->ordered()->take(3)->get(),
            'testimonials' => Testimonial::published()->take(6)->get(),
            'posts' => Post::published()->latest('published_at')->take(3)->get(),
            'statuses' => NetworkStatus::ordered()->get(),
            'promotions' => Promotion::live()->where('placement', 'homepage')->get(),
        ]);
    }

    public function about()
    {
        return view('site.about');
    }

    public function internet(Request $request)
    {
        $segment = $request->query('type'); // home, business, enterprise

        return view('site.internet', [
            'segment' => $segment,
            'packages' => Package::published()->category($segment)->ordered()->get(),
        ]);
    }

    public function solutions()
    {
        return view('site.solutions');
    }

    public function packages(Request $request)
    {
        $category = $request->query('category');

        return view('site.packages', [
            'category' => $category,
            'packages' => Package::published()->category($category)->ordered()->get(),
            'categories' => Package::published()->distinct()->pluck('category')->filter()->values(),
        ]);
    }

    public function app()
    {
        return view('site.app');
    }

    public function faq()
    {
        return view('site.faq', ['faqs' => Faq::published()->get()->groupBy('category')]);
    }

    public function speedTest()
    {
        return view('site.speed-test');
    }

    public function networkStatus()
    {
        return view('site.network-status', ['statuses' => NetworkStatus::ordered()->get()]);
    }

    public function support()
    {
        return view('site.support', ['faqs' => Faq::published()->take(6)->get()]);
    }

    public function legal($page)
    {
        $allowed = ['privacy-policy', 'terms-and-conditions', 'acceptable-use-policy'];

        abort_unless(in_array($page, $allowed, true), 404);

        return view('site.legal.'.$page);
    }
}
