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
        Schema::table('coupons', function (Blueprint $table) {
            // Đơn tối thiểu để sử dụng coupon
            if (!Schema::hasColumn('coupons', 'min_order_value')) {
                $table->decimal('min_order_value', 12, 2)->nullable()->after('discount_value')->comment('Đơn hàng tối thiểu để sử dụng coupon');
            }
            
            // Giới hạn giảm giá tối đa (cho coupon phần trăm)
            if (!Schema::hasColumn('coupons', 'max_discount')) {
                $table->decimal('max_discount', 12, 2)->nullable()->after('min_order_value')->comment('Giảm giá tối đa cho coupon phần trăm');
            }
            
            // Giới hạn số lần sử dụng toàn hệ thống
            if (!Schema::hasColumn('coupons', 'usage_limit')) {
                $table->integer('usage_limit')->nullable()->after('max_discount')->comment('Giới hạn số lần sử dụng toàn hệ thống');
            }
            
            // Giới hạn số lần sử dụng mỗi user
            if (!Schema::hasColumn('coupons', 'usage_per_user')) {
                $table->integer('usage_per_user')->nullable()->after('usage_limit')->comment('Giới hạn số lần sử dụng mỗi user');
            }
            
            // Đếm số lần đã sử dụng
            if (!Schema::hasColumn('coupons', 'used_count')) {
                $table->integer('used_count')->default(0)->after('usage_per_user')->comment('Số lần đã sử dụng');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            if (Schema::hasColumn('coupons', 'used_count')) {
                $table->dropColumn('used_count');
            }
            if (Schema::hasColumn('coupons', 'usage_per_user')) {
                $table->dropColumn('usage_per_user');
            }
            if (Schema::hasColumn('coupons', 'usage_limit')) {
                $table->dropColumn('usage_limit');
            }
            if (Schema::hasColumn('coupons', 'max_discount')) {
                $table->dropColumn('max_discount');
            }
            if (Schema::hasColumn('coupons', 'min_order_value')) {
                $table->dropColumn('min_order_value');
            }
        });
    }
};
