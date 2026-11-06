<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('cashier')->after('email');
        });

        // Carry the existing single admin login over as the owner account.
        $admin = DB::table('security_settings')->first();
        if ($admin) {
            DB::table('users')->where('email', 'test@example.com')->delete();
            DB::table('users')->updateOrInsert(
                ['email' => $admin->email],
                ['name' => DB::table('admin_settings')->value('name') ?: 'Owner', 'password' => $admin->password, 'role' => 'owner', 'created_at' => now(), 'updated_at' => now()]
            );
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
