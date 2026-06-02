<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            // $table->string('reference_number')->unique(); // e.g., ORD-59281
            $table->string('tracking_code', 10)->unique();
            // --- ADD THESE THREE LINES ---
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->text('delivery_location');
            
            $table->string('city_location');
            $table->text('addressOne_location');
            $table->text('order_note');
 
            // -----------------------------
            
            $table->decimal('total_amount', 10, 2);
            $table->enum('payment_method', ['cash', 'haram_transfer']); // وسيلة الدفع
            $table->enum('status', ['pending', 'confirmed', 'shipped', 'delivered', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
