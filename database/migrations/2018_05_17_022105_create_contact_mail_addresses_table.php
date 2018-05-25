<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactMailAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_mail_addresses', function (Blueprint $table) {
            $table->text('id');
            $table->primary('id');
            $table->text('contact_id');
            $table->foreign('contact_id')->references('id')->on('contacts');
            $table->text('mailaddress');
            $table->integer('type')->comment('1:company, 2:home, 3:mobile');

            $table->timestamp('created');
            $table->text('created_id');
            $table->foreign('created_id')->references('id')->on('users');

            $table->timestamp('modified');
            $table->text('modified_id');
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
        Schema::dropIfExists('contact_mail_addresses');
    }
}
