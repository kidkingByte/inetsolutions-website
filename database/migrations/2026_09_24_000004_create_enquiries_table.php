<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enquiries', function (Blueprint $table) {
            $table->id();
            $table->string('type');                 // connection_request, coverage_request, business_inquiry, enterprise_inquiry, support_request, contact_form, callback_request, coverage_notify
            $table->string('status')->default('new');
            $table->string('full_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('customer_id')->nullable();
            $table->string('region')->nullable();
            $table->string('district')->nullable();
            $table->string('ward')->nullable();
            $table->string('street')->nullable();
            $table->text('address')->nullable();
            $table->string('service_required')->nullable();
            $table->string('preferred_package')->nullable();
            $table->date('preferred_installation_date')->nullable();
            $table->string('subject')->nullable();
            $table->string('problem_type')->nullable();
            $table->text('message')->nullable();
            $table->string('attachment')->nullable();
            $table->text('admin_notes')->nullable();
            $table->string('source')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enquiries');
    }
};
