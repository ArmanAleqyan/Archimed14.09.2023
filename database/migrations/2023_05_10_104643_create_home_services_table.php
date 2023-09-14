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
        Schema::create('home_services', function (Blueprint $table) {
            $table->id();
            $table->longText('CODE')->nullable();
            $table->longText('LABEL')->nullable();
            $table->longText('SHORT_LABEL')->nullable();
            $table->longText('DESCRIPTION')->nullable();
            $table->longText('SERV_GRP_LEVEL1')->nullable();
            $table->longText('SERV_GRP_LEVEL2')->nullable();
            $table->longText('FILIAL_CODE')->nullable();
            $table->longText('REGION')->nullable();
            $table->longText('type_viezd')->nullable();
            $table->longText('CITO')->nullable();
            $table->longText('PRICE')->nullable();
            $table->string('FILIAL_ID')->nullable();

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
        Schema::dropIfExists('home_services');
    }
};
