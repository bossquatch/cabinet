<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKeyAccessRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('key_access_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->contstrained();
            $table->string('user_email')->constrained('users');
            $table->string('purpose');
            $table->boolean('approved')->default(false);
            $table->dateTime('approved_at')->nullable();
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
        Schema::dropIfExists('key_access_requests');
    }
}
