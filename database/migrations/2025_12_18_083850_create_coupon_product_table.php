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
        if (!Schema::hasTable('coupon_product')) {
            Schema::create('coupon_product', function (Blueprint $table) {
                $table->id();
                // Sử dụng bigInteger để khớp với kiểu bigint của bảng coupons và products
                $table->bigInteger('coupon_id');
                $table->bigInteger('product_id');
                $table->timestamps();

                // Đảm bảo mỗi coupon chỉ liên kết với mỗi product 1 lần
                $table->unique(['coupon_id', 'product_id']);
                
                // Thêm foreign key constraints
                $table->foreign('coupon_id')
                      ->references('id')
                      ->on('coupons')
                      ->onDelete('cascade');
                      
                $table->foreign('product_id')
                      ->references('id')
                      ->on('products')
                      ->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupon_product');
    }
};
