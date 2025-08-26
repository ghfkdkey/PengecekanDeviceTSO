<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('floors', function (Blueprint $table) {
            $table->id('floor_id');
            $table->unsignedBigInteger('building_id');
            $table->string('floor_name', 50);
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('building_id')->references('building_id')->on('buildings')->onDelete('cascade');
          
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('floors');
    }
};