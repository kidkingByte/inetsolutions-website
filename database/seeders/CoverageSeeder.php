<?php

namespace Database\Seeders;

use App\Models\CoverageArea;
use Illuminate\Database\Seeder;

class CoverageSeeder extends Seeder
{
    public function run(): void
    {
        // Example coverage records so the checker works out of the box.
        // Replace / expand from the admin dashboard with verified areas.
        $areas = [
            ['region' => 'Dar es Salaam', 'district' => 'Kinondoni', 'ward' => 'Mikocheni', 'status' => 'available', 'service_type' => 'home', 'technology' => 'fibre'],
            ['region' => 'Dar es Salaam', 'district' => 'Kinondoni', 'ward' => 'Masaki', 'status' => 'available', 'service_type' => 'business', 'technology' => 'fibre'],
            ['region' => 'Dar es Salaam', 'district' => 'Ilala', 'ward' => 'Kariakoo', 'status' => 'available', 'service_type' => 'business', 'technology' => 'wireless'],
            ['region' => 'Dar es Salaam', 'district' => 'Temeke', 'ward' => 'Kigamboni', 'status' => 'coming_soon', 'service_type' => 'home', 'technology' => '4g'],
            ['region' => 'Arusha', 'district' => 'Arusha City', 'ward' => 'Leletu', 'status' => 'under_expansion', 'service_type' => 'home', 'technology' => 'wireless'],
        ];

        foreach ($areas as $area) {
            CoverageArea::updateOrCreate(
                ['region' => $area['region'], 'district' => $area['district'], 'ward' => $area['ward']],
                $area + ['installation_available' => true]
            );
        }
    }
}
