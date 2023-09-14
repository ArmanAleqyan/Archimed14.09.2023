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
        Schema::create('pam_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('client_id')->nullable();
            $table->string('user_id')->nullable();
            $table->longText('title')->nullable();
            $table->longText('description')->nullable();
            $table->string('status')->default(1);
            $table->string('test')->nullable();
            $table->string('testtest')->nullable(1);
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
        Schema::dropIfExists('pam_notifications');
    }
};
