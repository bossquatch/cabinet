<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriverFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('driver_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained();
            $table->string('name', 60);
            $table->string('display_name', 80);
            $table->boolean('encrypt')->default(false);
            $table->boolean('is_file')->default(false);
            $table->boolean('required')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('driver_fields');
    }
}
