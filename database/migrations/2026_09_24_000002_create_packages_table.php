<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category')->default('home'); // home, business, enterprise
            $table->string('speed')->nullable();
            $table->string('download_speed')->nullable();
            $table->string('upload_speed')->nullable();
            $table->decimal('price', 12, 2)->default(0);
            $table->decimal('installation_fee', 12, 2)->default(0);
            $table->string('validity')->default('30 days');
            $table->unsignedTinyInteger('recommended_users')->nullable();
            $table->string('router_info')->nullable();
            $table->text('fair_usage_policy')->nullable();
            $table->string('installation_time')->nullable();
            $table->text('description')->nullable();
            $table->json('features')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
