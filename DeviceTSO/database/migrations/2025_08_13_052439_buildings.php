<?php

// database/migrations/xxxx_xx_xx_create_buildings_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('buildings', function (Blueprint $table) {
            $table->bigIncrements('building_id');
            $table->string('building_code', 50)->unique();
            $table->string('building_name', 100);
            $table->unsignedBigInteger('regional_id');
            $table->unsignedBigInteger('user_id'); 
            $table->timestamps();

            $table->foreign('regional_id')->references('regional_id')->on('regionals')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
    public function down(): void {
        Schema::dropIfExists('buildings');
    }
};