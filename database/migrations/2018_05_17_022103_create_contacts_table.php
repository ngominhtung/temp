<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->text('id');
            $table->primary('id');
            $table->integer('attribute')->comment('1: personal, 2: company')->nullable();
            $table->text('name')->nullable();
            $table->text('yomi_name')->nullable();
            $table->text('company_id')->nullable();
            $table->text('group_id')->nullable()->references('id')->on('company_groups');
            $table->date('birthday')->nullable();
            $table->text('memo')->nullable();
            $table->text('share_unit')->comment('company:企業, group:グループ, user: ユーザ')->nullable();
            $table->text('share_id')->nullable();
            $table->integer('status')->comment('1: register, 2:delete')->nullable();

            $table->timestamp('created');
            $table->text('created_id');
            $table->foreign('created_id')->references('id')->on('users');

            $table->timestamp('modified');
            $table->text('modified_id');
            $table->foreign('modified_id')->references('id')->on('users');

            $table->timestamp('deleted')->nullable();
            $table->text('deleted_id')->nullable();
            $table->foreign('deleted_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contacts');
    }
}
