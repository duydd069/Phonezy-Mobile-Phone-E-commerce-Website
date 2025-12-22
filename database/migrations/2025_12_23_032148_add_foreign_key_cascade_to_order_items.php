<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Try to drop existing foreign key if it exists
            // Different migration systems may have created it with different names
            try {
                DB::statement('ALTER TABLE order_items DROP FOREIGN KEY fk_oitems_order');
            } catch (\Exception $e) {
                // Foreign key doesn't exist, that's fine
            }
            
            try {
                DB::statement('ALTER TABLE order_items DROP INDEX fk_oitems_order');
            } catch (\Exception $e) {
                // Index doesn't exist, that's fine  
            }
            
            // Add foreign key with cascade delete
            $table->foreign('order_id', 'fk_order_items_order_cascade')
                ->references('id')
                ->on('orders')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Drop cascade foreign key
            $table->dropForeign(['order_id']);
            
            // Re-add simple foreign key without cascade
            $table->foreign('order_id')
                ->references('id')
                ->on('orders');
        });
    }
};
