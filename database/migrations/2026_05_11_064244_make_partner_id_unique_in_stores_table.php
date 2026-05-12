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
        // Cleanup duplicates before applying unique constraint
        $partnerIdsWithDuplicates = DB::table('stores')
            ->select('partner_id')
            ->groupBy('partner_id')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('partner_id');

        foreach ($partnerIdsWithDuplicates as $partnerId) {
            // Keep the first store, delete the rest
            $firstStoreId = DB::table('stores')
                ->where('partner_id', $partnerId)
                ->orderBy('id', 'asc')
                ->value('id');

            DB::table('stores')
                ->where('partner_id', $partnerId)
                ->where('id', '!=', $firstStoreId)
                ->delete();
        }

        Schema::table('stores', function (Blueprint $table) {
            $table->unique('partner_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropUnique(['partner_id']);
        });
    }
};
