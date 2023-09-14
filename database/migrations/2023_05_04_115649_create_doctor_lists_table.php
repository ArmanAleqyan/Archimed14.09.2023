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
        Schema::create('doctor_lists', function (Blueprint $table) {
            $table->id();
            $table->string('MEDECINS_ID')->nullable();
            $table->string('med_arch')->nullable();
            $table->string('Fam_doctor')->nullable();
            $table->string('om_doctor')->nullable();
            $table->string('FIO')->nullable();
            $table->longText('DESCRIPT')->nullable();
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
        Schema::dropIfExists('doctor_lists');
    }
};
