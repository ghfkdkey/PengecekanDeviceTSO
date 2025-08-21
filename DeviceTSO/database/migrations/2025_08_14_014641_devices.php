<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id('device_id');
            $table->unsignedBigInteger('room_id');
            $table->string('device_name', 100);
            $table->string('device_type', 50)->nullable();
            $table->string('serial_number', 100)->nullable();
            $table->string('image_path')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('room_id')->references('room_id')->on('rooms')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
