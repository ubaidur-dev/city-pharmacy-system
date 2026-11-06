<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('store_settings', function (Blueprint $table) {
            $table->id();
            $table->string('store_name')->default('City Pharmacy');
            $table->string('currency', 10)->default('Rs.');
            $table->decimal('tax_rate', 5, 2)->default(0);
            $table->unsignedInteger('low_stock_threshold')->default(10);
            $table->unsignedInteger('expiry_warning_days')->default(90);
            $table->timestamps();
        });

        // Keep the tax the old billing screen was using so existing behaviour is unchanged until the owner edits it.
        DB::table('store_settings')->insert(['tax_rate' => 1.5, 'created_at' => now(), 'updated_at' => now()]);
    }

    public function down(): void
    {
        Schema::dropIfExists('store_settings');
    }
};
