<?php

// database/migrations/xxxx_xx_xx_create_areas_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('areas', function (Blueprint $table) {
            $table->bigIncrements('area_id');
            $table->string('area_name', 100);
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
    public function down(): void {
        Schema::dropIfExists('areas');
    }
};