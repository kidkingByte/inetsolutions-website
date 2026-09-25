<?php

namespace Database\Seeders;

use App\Models\NetworkStatus;
use Illuminate\Database\Seeder;

class NetworkStatusSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            'Internet Services',
            'Customer Portal',
            'Payment Services',
            'Support Services',
        ];

        foreach ($services as $i => $service) {
            NetworkStatus::updateOrCreate(['service' => $service], [
                'status' => 'operational',
                'message' => null,
                'sort_order' => $i,
            ]);
        }
    }
}
