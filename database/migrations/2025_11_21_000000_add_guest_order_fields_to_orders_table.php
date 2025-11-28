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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('email')->nullable()->after('user_id');
            $table->string('session_id')->nullable()->after('email');
            $table->timestamp('email_verified_at')->nullable()->after('session_id');
            $table->string('verification_token')->nullable()->after('email_verified_at');
            $table->string('customer_name')->nullable()->after('verification_token');
            $table->string('customer_phone')->nullable()->after('customer_name');
            $table->text('shipping_address')->nullable()->after('customer_phone');

            // Cho phép user_id nullable cho người chưa đăng nhập
            $table->unsignedBigInteger('user_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'email',
                'session_id',
                'email_verified_at',
                'verification_token',
                'customer_name',
                'customer_phone',
                'shipping_address',
            ]);
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
        });
    }
};

