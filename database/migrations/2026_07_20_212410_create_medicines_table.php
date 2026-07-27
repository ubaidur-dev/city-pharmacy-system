<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     */
    public function up(): void
    {
        Schema::create('medicines', function (Blueprint $table) {
        $table->id();
        $table->string('name');           
        $table->string('category');       
        $table->string('company');        
        $table->decimal('price', 8, 2);   
        $table->integer('stock');         
        $table->date('expiry_date');      
        $table->timestamps();
    });
    }

    /**
     */
    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
