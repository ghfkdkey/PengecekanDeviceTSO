<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('device_check_results', function (Blueprint $table) {
            $table->id('result_id');
            $table->unsignedBigInteger('device_id');
            $table->unsignedBigInteger('checklist_id');
            $table->unsignedBigInteger('user_id');
            $table->string('status', 10);
            $table->text('notes')->nullable();
            $table->dateTime('checked_at')->nullable();
            $table->timestamps();

            $table->foreign('device_id')->references('device_id')->on('devices')->onDelete('cascade');
            $table->foreign('checklist_id')->references('checklist_id')->on('checklist_items')->onDelete('cascade');
            // UBAH: merujuk ke 'id' bukan 'user_id' di tabel users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('device_check_results');
    }
};