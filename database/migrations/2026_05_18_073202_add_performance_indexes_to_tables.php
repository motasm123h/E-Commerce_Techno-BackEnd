<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Add indexes to the Products table
        Schema::table('products', function (Blueprint $table) {
            $table->index('name'); // Speeds up text search
            $table->index('price'); // Speeds up min/max price filters
            $table->index('brand_id'); // Speeds up brand filtering
            $table->index('section_id'); 
            $table->index('is_active');
        });

        // Add indexes to the Orders table
        Schema::table('orders', function (Blueprint $table) {
            $table->index('tracking_code'); // Speeds up order lookups
            $table->index('status'); // Speeds up the admin dashboard calculations
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['name']);
            $table->dropIndex(['price']);
            $table->dropIndex(['brand_id']);
            $table->dropIndex(['section_id']);
            $table->dropIndex(['is_active']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['reference_number']);
            $table->dropIndex(['status']);
        });
    }
};