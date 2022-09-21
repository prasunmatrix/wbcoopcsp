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
            $table->increments('id');
            $table->string('full_name');
            $table->string('email')->nullable();
            $table->enum('is_email_verified',['0','1'])->nullable();
            $table->string('phone_no')->nullable();
            $table->enum('is_ph_no_verified',['0','1'])->nullable();
            $table->string('password');
            $table->enum('registration_channel',['Website', 'Admin', 'Android', 'iOS'])->comment('Website, Admin, Android, iOS')->nullable();
            $table->integer('role_id')->nullable();
            $table->enum('is_customer',['0','1'])->nullable();
            $table->enum('is_wholesaler',['0','1'])->nullable();
            $table->enum('is_contractor',['0','1'])->nullable();
            $table->integer('lastlogintime')->nullable();
            $table->rememberToken()->nullable();
            $table->enum('status',['0','1'])->default('0')->comment('0=Inactive, 1=active');
            $table->timestamps();
            $table->dateTime('deleted_at')->nullable();
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
