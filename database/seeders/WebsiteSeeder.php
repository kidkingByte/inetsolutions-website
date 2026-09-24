<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class WebsiteSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedAdmin();
        $this->seedSettings();
        (new PackageSeeder)->run();
        (new CoverageSeeder)->run();
        (new FaqSeeder)->run();
        (new NetworkStatusSeeder)->run();
        (new ContentSeeder)->run();
    }

    protected function seedAdmin(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@inetsolutions.co.tz'],
            [
                'name' => 'INET Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );
    }

    protected function seedSettings(): void
    {
        $settings = [
            // general / brand
            ['company_name', 'INET SOLUTIONS LTD', 'general', 'text', 'Company name'],
            ['site_title', 'INET SOLUTIONS LTD | Reliable Internet & ICT Solutions in Tanzania', 'general', 'text', 'Site title (SEO)'],
            ['meta_description', 'INET SOLUTIONS LTD provides reliable internet, business connectivity, Wi-Fi and network solutions for homes, businesses and organizations in Tanzania.', 'general', 'textarea', 'Meta description'],
            ['tagline', 'Connecting You to What Matters.', 'general', 'text', 'Tagline'],
            ['tagline_alt', 'Fast. Reliable. Connected.', 'general', 'text', 'Alternative tagline'],
            ['tagline_sw', 'Mtandao Unaokuwezesha.', 'general', 'text', 'Swahili tagline'],
            ['positioning', 'Reliable Internet & Digital Connectivity Solutions for Homes, Businesses and Organizations.', 'general', 'text', 'Positioning statement'],

            // hero
            ['hero_heading', 'Reliable Internet. Built Around Your Needs.', 'hero', 'text', 'Hero heading'],
            ['hero_subheading', 'Stay connected with fast, reliable and affordable internet solutions designed for homes, businesses and organizations across Tanzania.', 'hero', 'textarea', 'Hero subheading'],

            // about
            ['about_heading', 'Your Trusted Connectivity Partner', 'about', 'text', 'About heading'],
            ['about_body', "INET SOLUTIONS LTD is a Tanzania-based technology and internet service provider focused on delivering reliable connectivity and digital solutions to individuals, businesses and organizations.\n\nWe combine modern networking technologies, responsive customer support and flexible internet packages to help our customers stay connected, productive and competitive in a digital world.\n\nFrom home internet to business connectivity, our solutions are designed around the real needs of our customers.", 'about', 'textarea', 'About body'],
            ['mission', 'To provide reliable, accessible and innovative connectivity solutions that empower people, businesses and organizations to communicate, work, learn and grow.', 'about', 'textarea', 'Mission'],
            ['vision', 'To become a trusted connectivity and digital solutions partner across Tanzania.', 'about', 'textarea', 'Vision'],

            // contact
            ['phone', '+255 774 111 444', 'contact', 'text', 'Phone'],
            ['email', 'info@inetsolutions.co.tz', 'contact', 'text', 'Email'],
            ['support_email', 'support@inetsolutions.co.tz', 'contact', 'text', 'Support email'],
            ['sales_email', 'sales@inetsolutions.co.tz', 'contact', 'text', 'Sales email'],
            ['whatsapp', '255774111444', 'contact', 'text', 'WhatsApp number (no +)'],
            ['address', 'Dar es Salaam, Tanzania', 'contact', 'text', 'Address'],

            // app
            ['app_play_url', 'https://play.google.com/store/apps/details?id=com.inetapp.cosfix', 'app', 'url', 'Google Play link'],
            ['app_store_url', '', 'app', 'url', 'App Store link'],

            // hours
            ['working_hours', "Monday – Friday: 08:00 – 18:00\nSaturday: 09:00 – 14:00\nSunday: Emergency Support", 'hours', 'textarea', 'Working hours'],
        ];

        foreach ($settings as [$key, $value, $group, $type, $label]) {
            SiteSetting::updateOrCreate(['key' => $key], compact('value', 'group', 'type', 'label'));
        }

        // JSON/list settings
        $json = [
            ['social_links', [
                'facebook' => '', 'instagram' => '', 'tiktok' => '', 'linkedin' => '', 'youtube' => '',
            ], 'social', 'Facebook page, Instagram, etc. (fill in before launch)'],
            ['core_values', [
                ['title' => 'Reliability', 'text' => 'We build services our customers can depend on.'],
                ['title' => 'Customer First', 'text' => 'Our customers are at the center of everything we do.'],
                ['title' => 'Innovation', 'text' => 'We continuously adopt better technologies and smarter solutions.'],
                ['title' => 'Transparency', 'text' => 'We believe in clear pricing, communication and service.'],
                ['title' => 'Security', 'text' => 'We take network and customer data security seriously.'],
                ['title' => 'Excellence', 'text' => 'We continuously improve the quality of our services.'],
            ], 'about', 'Core values'],
            ['why_inet', [
                ['title' => 'Reliable Connectivity', 'text' => 'Stay connected with dependable internet services designed for everyday and business needs.'],
                ['title' => 'Flexible Packages', 'text' => 'Choose a plan that matches your connectivity requirements and budget.'],
                ['title' => 'Customer Support', 'text' => 'Get assistance whenever you need help with your connection or account.'],
                ['title' => 'Modern Technology', 'text' => 'We continuously adopt modern networking technologies to improve connectivity and customer experience.'],
                ['title' => 'Convenient Payments', 'text' => 'Manage your internet service using supported and convenient payment channels.'],
                ['title' => 'Business Solutions', 'text' => 'Scalable connectivity solutions designed for businesses and organizations.'],
            ], 'general', 'Why choose INET'],
        ];

        foreach ($json as [$key, $value, $group, $label]) {
            SiteSetting::updateOrCreate(
                ['key' => $key],
                ['value' => json_encode($value), 'group' => $group, 'type' => 'json', 'label' => $label]
            );
        }

        SiteSetting::flush();
    }
}
