<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_groups', function (Blueprint $table) {
            $table->text('id');
            $table->primary('id');

            $table->text('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->text('group_id');
            $table->foreign('group_id')->references('id')->on('groups');
            $table->index('user_id','group_id');

            $table->timestamp('created')->useCurrent();
            $table->text('created_id');
            $table->foreign('created_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_groups');
    }
}
