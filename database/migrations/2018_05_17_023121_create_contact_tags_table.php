<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('contact_tags', function (Blueprint $table) {
       $table->text('id');
       $table->primary('id');
       $table->text('contact_id');
       $table->foreign('contact_id')->references('id')->on('contacts');
       $table->text('tag_id');
       $table->foreign('tag_id')->references('id')->on('tags');

       $table->timestamp('created')->useCurrent()->nullable();
       $table->text('created_id')->nullable();
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
      Schema::dropIfExists('contact_tags');
    }
  }
