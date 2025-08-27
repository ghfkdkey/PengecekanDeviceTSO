<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Tambah foreign key users → regionals
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'regional_id')) {
                $table->unsignedBigInteger('regional_id')->nullable()->after('role');
            }
            $table->foreign('regional_id')
                  ->references('regional_id')
                  ->on('regionals')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['regional_id']);
            // jangan drop kolom kalau sudah ada sejak awal
            if (Schema::hasColumn('users', 'regional_id')) {
                $table->dropColumn('regional_id');
            }
        });
    }
};