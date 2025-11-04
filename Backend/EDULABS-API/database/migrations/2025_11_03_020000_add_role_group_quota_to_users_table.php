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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->after('id')->constrained('roles');
            $table->foreignId('grupo_id')->nullable()->after('role_id')->constrained('grupos')->nullOnDelete();
            // Nullable per-user quota override in bytes; NULL => use group or global
            $table->unsignedBigInteger('quota_bytes')->nullable()->after('password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('quota_bytes');
            $table->dropConstrainedForeignId('grupo_id');
            $table->dropConstrainedForeignId('role_id');
        });
    }
};


