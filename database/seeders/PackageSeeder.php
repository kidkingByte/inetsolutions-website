<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        // NOTE: speeds/prices are intentionally left empty ("to be confirmed")
        // per the content specification. Admins fill them in from the dashboard.
        $packages = [
            ['name' => 'Home Basic', 'category' => 'home', 'recommended_users' => 3, 'description' => 'Great for light browsing, social media and messaging for 1-3 users.', 'features' => ['Reliable connectivity', 'Flexible packages', 'Fast installation', 'Customer support'], 'sort_order' => 1],
            ['name' => 'Home Plus', 'category' => 'home', 'recommended_users' => 5, 'is_featured' => true, 'description' => 'Ideal for families who stream, study and work from home.', 'features' => ['Reliable connectivity', 'Great for streaming', 'Usage monitoring', 'Convenient payments'], 'sort_order' => 2],
            ['name' => 'Home Premium', 'category' => 'home', 'recommended_users' => 8, 'description' => 'For heavy users: multiple HD streams, gaming and large downloads.', 'features' => ['High-speed browsing', 'HD streaming ready', 'Gaming friendly', 'Priority support'], 'sort_order' => 3],
            ['name' => 'Business Basic', 'category' => 'business', 'recommended_users' => 10, 'description' => 'Business-grade connectivity for small offices and shops.', 'features' => ['Business-grade connectivity', 'Flexible bandwidth', 'Network monitoring', 'Installation support'], 'sort_order' => 4],
            ['name' => 'Business Pro', 'category' => 'business', 'recommended_users' => 25, 'is_featured' => true, 'description' => 'Scalable connectivity for SMEs, hotels and schools.', 'features' => ['Scalable packages', 'Cloud application ready', 'Priority support', 'Service level options'], 'sort_order' => 5],
            ['name' => 'Enterprise', 'category' => 'enterprise', 'description' => 'Customised connectivity for organizations with demanding requirements.', 'features' => ['Dedicated internet access', 'Site-to-site / VPN', 'Managed connectivity', 'Dedicated account support'], 'sort_order' => 6],
        ];

        foreach ($packages as $data) {
            Package::updateOrCreate(['name' => $data['name']], $data + [
                'validity' => '30 days',
                'is_published' => true,
            ]);
        }
    }
}
