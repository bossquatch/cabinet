<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDiskTemplate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('disks', function ($table) {
            $table->foreignId('template_id')->nullable()->after('backup_id')->constrained('disks')->onDelete('set null');
            $table->boolean('is_template')->default(false)->after('encode_files');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('disks', function ($table) {
            $table->dropColumn('template_id');
            $table->dropColumn('is_template');
        });
    }
}
