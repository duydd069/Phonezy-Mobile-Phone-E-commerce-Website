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
        if (!Schema::hasTable('coupon_user')) {
            Schema::create('coupon_user', function (Blueprint $table) {
                $table->id();
                // Sử dụng bigInteger để khớp với kiểu bigint (không unsigned) của bảng coupons
                $table->bigInteger('coupon_id');
                $table->bigInteger('user_id');
                $table->timestamps();

                // Đảm bảo mỗi user chỉ có thể dùng 1 coupon 1 lần
                $table->unique(['coupon_id', 'user_id']);
                
                // Thêm foreign key constraints
                $table->foreign('coupon_id')
                      ->references('id')
                      ->on('coupons')
                      ->onDelete('cascade');
                      
                $table->foreign('user_id')
                      ->references('id')
                      ->on('users')
                      ->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupon_user');
    }
};
