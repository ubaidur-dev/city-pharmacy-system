<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->string('patient_name'); 
            $table->string('phone')->nullable();
            $table->string('channel')->default('In-Store'); 
            $table->string('payment_method')->default('Cash Payment'); 
            $table->integer('total_items')->default(1);
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0); 
            $table->decimal('paid_amount', 10, 2)->default(0);  
            $table->decimal('cash_received', 10, 2)->default(0); 
            $table->decimal('change_due', 10, 2)->default(0);    
            $table->json('cart_items')->nullable(); 
            $table->timestamps(); 
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};