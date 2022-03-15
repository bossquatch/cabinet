<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSharedKeysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shared_keys', function (Blueprint $table) {
            $table->id();
            $table->integer('key_id')->contstrained();
            $table->foreignId('owner_id')->constrained('users');
            $table->string('shared_email', 100)->constrained('users');
            $table->string('description', 100);
            $table->string('value', 50);
            $table->boolean('public')->default(false);
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
        Schema::dropIfExists('shared_keys');
    }
}
