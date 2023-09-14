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
        Schema::create('user_directions', function (Blueprint $table) {
            $table->id();
            $table->string('PATDIREC_ID')->nullable();
            $table->string('DATE_PATDIR')->nullable();
            $table->string('NAME')->nullable();
            $table->string('DESCR_PATDIR')->nullable();
            $table->string('FIO_MED')->nullable();
            $table->string('CLINIC')->nullable();
            $table->string('PATIENTS_ID')->nullable();
            $table->string('STATE_PATDIR')->nullable();
            $table->string('DATE_ACTUAL')->nullable();
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
        Schema::dropIfExists('user_directions');
    }
};
