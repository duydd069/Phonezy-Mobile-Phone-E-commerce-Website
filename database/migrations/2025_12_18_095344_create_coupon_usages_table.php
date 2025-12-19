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
        if (!Schema::hasTable('coupon_usages')) {
            Schema::create('coupon_usages', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('coupon_id');
            $table->bigInteger('user_id')->nullable()->comment('NULL nếu là guest');
            $table->unsignedBigInteger('order_id')->nullable()->comment('ID đơn hàng đã sử dụng coupon');
            $table->timestamp('used_at')->useCurrent();
            
            // Index để query nhanh
            $table->index(['coupon_id', 'user_id']);
            $table->index('order_id');
            $table->index('used_at');
            
            // Foreign keys
            $table->foreign('coupon_id')
                  ->references('id')
                  ->on('coupons')
                  ->onDelete('cascade');
                  
            $table->foreign('order_id')
                  ->references('id')
                  ->on('orders')
                  ->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupon_usages');
    }
};
