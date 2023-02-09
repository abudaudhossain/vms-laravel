<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->unsignedBigInteger('meetingWith');
            $table->foreign('meetingWith')->references('id')->on('users');
            $table->timestamp('date');
            $table->string('email')->nullable();
            $table->string('purpose')->nullable();
            $table->string('image')->nullable();
            $table->tinyInteger('type')->default(0);
            /* Users: 0=>spot, 1=>scheduled*/
            $table->unsignedBigInteger('organization_id');
            $table->foreign('organization_id')->references('id')->on('organizations');
            $table->tinyInteger('status')->default(0);
            /* Users: 0=>pending, 1=>canceled 2=>waiting 3=>completed*/
            $table->unsignedBigInteger('createdBy');
            $table->foreign('createdBy')->references('id')->on('users');
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
        Schema::dropIfExists('meetings');
    }
};
