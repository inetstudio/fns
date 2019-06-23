<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateFnsAccountsTables.
 */
class CreateFnsAccountsTables extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('fns_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email');
            $table->string('login');
            $table->string('password');
            $table->timestamp('blocked_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('fns_accounts');
    }
}
