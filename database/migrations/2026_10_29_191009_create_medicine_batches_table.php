<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medicine_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicine_id')->constrained()->cascadeOnDelete();
            $table->string('batch_no');
            $table->date('expiry_date');
            $table->unsignedInteger('quantity')->default(0);
            $table->decimal('cost_price', 10, 2)->nullable();
            $table->timestamps();
            $table->index(['medicine_id', 'expiry_date']);
        });

        // Every existing medicine becomes one opening batch so no stock is lost.
        DB::table('medicines')->orderBy('id')->each(function ($m) {
            DB::table('medicine_batches')->insert([
                'medicine_id' => $m->id,
                'batch_no' => 'OPENING',
                'expiry_date' => $m->expiry_date,
                'quantity' => max(0, (int) $m->stock),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medicine_batches');
    }
};
