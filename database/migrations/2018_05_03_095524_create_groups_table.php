<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->text('id');
            $table->primary('id');
            $table->text('name');
            $table->text('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on('groups');

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
        Schema::dropIfExists('groups');
    }
}
