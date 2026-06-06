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
        // MySQL specific way to update ENUM columns
        Schema::table('vouchers', function (Blueprint $table) {
            \DB::statement("ALTER TABLE vouchers MODIFY COLUMN status ENUM('active', 'claimed', 'refund_pending', 'refunded', 'expired') DEFAULT 'active'");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            \DB::statement("ALTER TABLE vouchers MODIFY COLUMN status ENUM('active', 'claimed', 'refunded', 'expired') DEFAULT 'active'");
        });
    }
};
