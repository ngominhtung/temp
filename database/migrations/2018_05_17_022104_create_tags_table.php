<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->text('id');
            $table->primary('id');
            $table->text('name')->nullable();
            
            $table->timestamp('created')->nullable();
            $table->text('created_id')->nullable();
            $table->foreign('created_id')->references('id')->on('users');

            $table->timestamp('modified')->nullable();
            $table->text('modified_id')->nullable();
            $table->foreign('modified_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tags');
    }
}
