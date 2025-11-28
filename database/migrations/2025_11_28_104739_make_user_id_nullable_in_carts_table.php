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
        Schema::table('carts', function (Blueprint $table) {
            // Drop foreign key constraint
            $table->dropForeign(['user_id']);
            // Make user_id nullable
            $table->unsignedBigInteger('user_id')->nullable()->change();
            // Re-add foreign key with nullable
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            // Drop foreign key
            $table->dropForeign(['user_id']);
            // Make user_id required again (only if no null values exist)
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
            // Re-add foreign key constraint
            $table->foreign('user_id')->references('id')->on('users');
        });
    }
};
