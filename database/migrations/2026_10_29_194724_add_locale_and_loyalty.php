<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', fn (Blueprint $t) => $t->string('locale', 5)->nullable()->after('role'));
        Schema::table('customers', fn (Blueprint $t) => $t->unsignedInteger('points')->default(0)->after('notes'));
        Schema::table('orders', function (Blueprint $t) {
            $t->unsignedInteger('points_earned')->default(0);
            $t->unsignedInteger('points_redeemed')->default(0);
        });
        Schema::table('store_settings', function (Blueprint $t) {
            $t->decimal('loyalty_earn_per', 10, 2)->default(0);  // spend this much to earn 1 point; 0 turns loyalty off
            $t->decimal('loyalty_point_value', 10, 4)->default(1); // currency value of 1 point when redeemed
        });
    }

    public function down(): void
    {
        Schema::table('store_settings', fn (Blueprint $t) => $t->dropColumn(['loyalty_earn_per', 'loyalty_point_value']));
        Schema::table('orders', fn (Blueprint $t) => $t->dropColumn(['points_earned', 'points_redeemed']));
        Schema::table('customers', fn (Blueprint $t) => $t->dropColumn('points'));
        Schema::table('users', fn (Blueprint $t) => $t->dropColumn('locale'));
    }
};
