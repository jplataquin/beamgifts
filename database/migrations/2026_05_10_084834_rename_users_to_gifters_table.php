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
        Schema::rename('users', 'gifters');
        
        Schema::table('sessions', function (Blueprint $table) {
            $table->renameColumn('user_id', 'gifter_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sessions', function (Blueprint $table) {
            $table->renameColumn('gifter_id', 'user_id');
        });
        
        Schema::rename('gifters', 'users');
    }
};
