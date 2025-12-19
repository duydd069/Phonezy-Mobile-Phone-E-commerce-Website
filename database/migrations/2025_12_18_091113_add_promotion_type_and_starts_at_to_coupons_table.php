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
            // Thêm promotion_type nếu chưa có
            if (!Schema::hasColumn('coupons', 'promotion_type')) {
                $table->enum('promotion_type', ['order', 'product'])->default('order')->after('type');
            }
            // Thêm starts_at nếu chưa có
            if (!Schema::hasColumn('coupons', 'starts_at')) {
                $table->timestamp('starts_at')->nullable()->after('discount_value');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            if (Schema::hasColumn('coupons', 'promotion_type')) {
                $table->dropColumn('promotion_type');
            }
            if (Schema::hasColumn('coupons', 'starts_at')) {
                $table->dropColumn('starts_at');
            }
        });
    }
};
