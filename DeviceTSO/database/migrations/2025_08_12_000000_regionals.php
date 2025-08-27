<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('regionals', function (Blueprint $table) {
            $table->bigIncrements('regional_id');
            $table->string('regional_name', 100);
            $table->unsignedBigInteger('area_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('area_id')->references('area_id')->on('areas')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
    public function down(): void {
        Schema::dropIfExists('regionals');
    }
};