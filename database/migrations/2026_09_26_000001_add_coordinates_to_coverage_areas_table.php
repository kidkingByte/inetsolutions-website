<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('coverage_areas', function (Blueprint $table) {
            // Optional exact map position; without it the map falls back to the region's centre.
            $table->decimal('latitude', 10, 7)->nullable()->after('street');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
        });
    }

    public function down(): void
    {
        Schema::table('coverage_areas', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude']);
        });
    }
};
