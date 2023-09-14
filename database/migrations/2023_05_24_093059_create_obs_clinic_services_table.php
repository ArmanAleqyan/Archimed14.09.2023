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
        Schema::create('obs_clinic_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('obs_id')->nullable();
            $table->foreign('obs_id')
                ->references('id')->on('obs_clinics')
                ->onDelete('cascade');

            $table->string('CODE')->nullable();
            $table->longText('LABEL')->nullable();
            $table->longText('SHORT_LABEL')->nullable();
            $table->longText('DESCRIPTION')->nullable();
            $table->string('PRICE')->nullable();
            $table->string('FILIAL_ID')->nullable();
            $table->string('FILIAL_CODE')->nullable();
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
        Schema::dropIfExists('obs_clinic_services');
    }
};
