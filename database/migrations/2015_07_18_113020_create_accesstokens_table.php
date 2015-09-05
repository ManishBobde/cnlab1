<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccesstokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accesstokens', function(Blueprint $table) {
            $table->increments('accessTokenId');
            $table->string('accessToken');
            $table->string('deviceType');//Type of Device-Mobile,Tab,PC
            $table->string('mediaType');//Browser or app
            $table->string('osName');//android/iphone
            $table->integer('idealTimeExpirationDuration');
            $table->integer('userId')->unsigned()->index();
            $table->foreign('userId')->references('userId')->on('users');
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
        Schema::drop('accesstokens');
    }
}
