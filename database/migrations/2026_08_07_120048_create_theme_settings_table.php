<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('theme_settings')) {
            Schema::create('theme_settings', function (Blueprint $table) {
                $table->id();
                $table->string('primary_color')->default('navy');
                $table->string('interface_mode')->default('light');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('theme_settings');
    }
};