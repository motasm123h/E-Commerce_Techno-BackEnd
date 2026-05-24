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
       Schema::create('shipping_zones', function (Blueprint $table) {
            $table->id();
            $table->string('city_name')->unique(); // e.g., 'Amsterdam'
            $table->decimal('fee', 8, 2); // e.g., 5.00
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('shipping_fee', 8, 2)->default(0)->after('total_amount');
            $table->string('shipping_city')->nullable()->after('delivery_location');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_zones');
    }
};
