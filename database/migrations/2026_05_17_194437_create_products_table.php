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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained()->onDelete('cascade');
            // $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('brand_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->decimal('price', 10, 2);
            $table->integer('stock')->default(0); // Add this
            $table->boolean('is_active')->default(true); // Add this
            $table->enum('component_type', [
                'case',
                'cpu',
                'motherboard',
                'graphic_card',
                'ram',
                'hard_disk',
                'optical_drive',
                'power_supply',
                'cooling_system',
                'fan',
                'monitor',
                'mouse',
                'keyboard',
                'headphone',
                'sound_system'
            ])->nullable();
            $table->json('images')->nullable();
            $table->json('colors')->nullable();
            $table->json('details')->nullable();
            $table->json('attributes')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
