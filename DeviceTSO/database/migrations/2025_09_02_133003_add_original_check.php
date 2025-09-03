<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('device_check_results', function (Blueprint $table) {
            $table->timestamp('updated_at_custom')->nullable()->after('notes');
            $table->timestamp('original_checked_at')->nullable()->after('updated_at_custom');
        });
        
        // Set original_checked_at untuk data existing
        DB::statement('UPDATE device_check_results SET original_checked_at = checked_at WHERE original_checked_at IS NULL');
    }

    public function down()
    {
        Schema::table('device_check_results', function (Blueprint $table) {
            $table->dropColumn(['updated_at_custom', 'original_checked_at']);
        });
    }
};