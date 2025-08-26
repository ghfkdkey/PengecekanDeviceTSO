<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('devices', function (Blueprint $table) {
            $table->string('category', 50)->default('office tools');
            $table->text('notes')->nullable();
            $table->string('merk', 100)->nullable();
            $table->year('tahun_po')->nullable();
            $table->string('no_po', 100)->nullable();
            $table->string('no_bast', 100)->nullable();
            $table->year('tahun_bast')->nullable();
            $table->enum('condition', ['baik', 'rusak'])->default('baik');
        });
    }

    public function down(): void {
        Schema::table('devices', function (Blueprint $table) {
            $table->dropColumn([
                'category', 'notes', 'merk', 'tahun_po',
                'no_po', 'no_bast', 'tahun_bast', 'condition'
            ]);
        });
    }
};