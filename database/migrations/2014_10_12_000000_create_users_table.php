<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->string('apple_id')->nullable();
            $table->string('lastName')->nullable();
            $table->string('firstName')->nullable();
            $table->string('middleName')->nullable();
            $table->string('gender')->nullable();
            $table->string('birthDate')->nullable();
            $table->string('email')->nullable();
            $table->string('email_verify_code')->nullable();
            $table->string('city_name')->nullable();
            $table->string('city_id')->nullable();
            $table->string('phone')->unique()->nullable();
            $table->string('phone_veryfi_code')->nullable();
            $table->string('phone_code')->nullable();
            $table->string('geo_dostup')->nullable();
            $table->string('role_id')->nullable();
            $table->string('password')->nullable();


            $table->string('user_key')->nullable();
            $table->longtext('client_token')->nullable();
            $table->longtext('client_refresh_token')->nullable();

            $table->string('Citizenship')->nullable();
            $table->string('Actual_Address')->nullable();
            $table->string('Place_of_Study')->nullable();
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
        Schema::dropIfExists('users');
    }
}
