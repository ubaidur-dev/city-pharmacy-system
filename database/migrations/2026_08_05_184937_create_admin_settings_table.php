<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('Ubaid Ur Rehman');
            $table->string('email')->default('ubaid@citypharmacy.com');
            $table->string('phone')->nullable()->default('+92 300 1234567');
            $table->string('designation')->default('Software Developer / Senior Admin');
            $table->string('address')->default('Karachi, Pakistan');
            $table->text('bio')->nullable();
            $table->string('profile_image')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_settings');
    }
};
