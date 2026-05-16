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
        // 1. Add new columns to partners table safely
        Schema::table('partners', function (Blueprint $table) {
            if (!Schema::hasColumn('partners', 'role')) {
                $table->string('role')->default('owner')->after('is_banned');
            }
            if (!Schema::hasColumn('partners', 'store_id')) {
                $table->foreignId('store_id')->nullable()->after('role')->constrained()->onDelete('cascade');
            }
            if (!Schema::hasColumn('partners', 'branch_id')) {
                $table->foreignId('branch_id')->nullable()->after('store_id')->constrained()->onDelete('cascade');
            }
            if (!Schema::hasColumn('partners', 'must_change_password')) {
                $table->boolean('must_change_password')->default(false)->after('branch_id');
            }
        });

        // 2. Data Migration: Set store_id for existing owners (partners)
        DB::statement("UPDATE partners p JOIN stores s ON s.partner_id = p.id SET p.store_id = s.id");

        // 3. Data Migration: Move managers to partners table
        $managers = DB::table('managers')->get();
        $idMap = [];

        foreach ($managers as $manager) {
            $newPartnerId = DB::table('partners')->insertGetId([
                'name' => $manager->name,
                'email' => $manager->email,
                'password' => $manager->password,
                'role' => 'manager',
                'store_id' => $manager->store_id,
                'branch_id' => $manager->branch_id,
                'must_change_password' => $manager->must_change_password,
                'remember_token' => $manager->remember_token,
                'created_at' => $manager->created_at,
                'updated_at' => $manager->updated_at,
            ]);
            $idMap[$manager->id] = $newPartnerId;
        }

        // 4. Data Migration: Update vouchers polymorphic relations
        foreach ($idMap as $oldId => $newId) {
            DB::table('vouchers')
                ->where('claimed_by_user_type', 'App\Models\Manager')
                ->where('claimed_by_user_id', $oldId)
                ->update([
                    'claimed_by_user_type' => 'App\Models\Partner',
                    'claimed_by_user_id' => $newId
                ]);
        }

        // 5. Clean up stores table
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn('partner_id');
        });

        // 6. Drop managers table
        Schema::dropIfExists('managers');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('managers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->onDelete('cascade');
            $table->foreignId('branch_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->boolean('must_change_password')->default(false);
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::table('stores', function (Blueprint $table) {
            $table->unsignedBigInteger('partner_id')->nullable()->after('id');
        });

        // Move managers back (best-effort)
        $managerPartners = DB::table('partners')->where('role', 'manager')->get();
        foreach ($managerPartners as $mp) {
            DB::table('managers')->insert([
                'store_id' => $mp->store_id,
                'branch_id' => $mp->branch_id,
                'name' => $mp->name,
                'email' => $mp->email,
                'password' => $mp->password,
                'must_change_password' => $mp->must_change_password,
                'remember_token' => $mp->remember_token,
                'created_at' => $mp->created_at,
                'updated_at' => $mp->updated_at,
            ]);
        }

        // Restore stores.partner_id
        DB::statement("UPDATE stores s JOIN partners p ON p.store_id = s.id AND p.role = 'owner' SET s.partner_id = p.id");

        Schema::table('partners', function (Blueprint $table) {
            $table->dropForeign(['store_id']);
            $table->dropForeign(['branch_id']);
            $table->dropColumn(['role', 'store_id', 'branch_id', 'must_change_password']);
        });
    }
};
