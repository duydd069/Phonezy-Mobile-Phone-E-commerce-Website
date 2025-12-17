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
        Schema::table('order_items', function (Blueprint $table) {
            // Check if column exists before adding
            if (!Schema::hasColumn('order_items', 'product_variant_id')) {
                $table->unsignedInteger('product_variant_id')->nullable()->after('product_id');
            }
        });
        
        // Add foreign key separately to avoid issues
        try {
            Schema::table('order_items', function (Blueprint $table) {
                $table->foreign('product_variant_id')->references('id')->on('product_variants')->onDelete('set null');
            });
        } catch (\Exception $e) {
            // Foreign key might already exist, ignore
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['product_variant_id']);
            $table->dropColumn('product_variant_id');
        });
    }
};
