<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->text('id');
            $table->primary('id');
            $table->text('name')->nullable();
            $table->text('yomi_name')->nullable();
            $table->text('mail_address')->unique();
            $table->text('company_name')->nullable();
            $table->date('birthday')->nullable();
            $table->text('memo')->nullable();
            $table->integer('authority')->comment('1: main, 2: sub, 3: common')->nullable();
            $table->integer('status')->comment('1: register, 2:delete')->nullable();
            $table->text('password')->nullable();

            $table->timestamp('created');
            $table->text('created_id');
            $table->foreign('created_id')->references('id')->on('users');

            $table->timestamp('modified');
            $table->text('modified_id');
            $table->foreign('modified_id')->references('id')->on('users');

            $table->timestamp('deleted')->nullable();
            $table->text('deleted_id')->nullable();
            $table->foreign('deleted_id')->references('id')->on('users');

            $table->rememberToken();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
