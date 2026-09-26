<?php

namespace Database\Seeders;

use App\Models\CoverageArea;
use Illuminate\Database\Seeder;

class CoverageSeeder extends Seeder
{
    public function run(): void
    {
        // INET operates across Zanzibar — all of Unguja and Pemba. Seeded at region level only;
        // admins add the verified districts and wards from the dashboard, which then appear in the checker.
        foreach (CoverageArea::ZANZIBAR_REGIONS as $region) {
            CoverageArea::firstOrCreate(
                ['region' => $region, 'district' => null, 'ward' => null],
                ['status' => 'available', 'installation_available' => false]
            );
        }
    }
}
