<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDiskLogsTokens extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('disk_logs', function ($table) {
            $table->foreignId('personal_access_token_id')->nullable()->after('user_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('disk_logs', function ($table) {
            $table->dropColumn('personal_access_token_id');
        });
    }
}
