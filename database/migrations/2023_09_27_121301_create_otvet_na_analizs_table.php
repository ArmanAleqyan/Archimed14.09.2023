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
        Schema::create('otvet_na_analizs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->unsignedBigInteger('client_db_id')->nullable();
            $table->string('exam_name')->nullable();
            $table->string('patdirec_id')->nullable();
            $table->string('Res_date')->nullable();
            $table->string('Date_bio')->nullable();
            $table->string('FIO_patient')->nullable();
            $table->string('BD_patients')->nullable();
            $table->string('Sex_patient')->nullable();
            $table->string('TYPE')->nullable();
            $table->string('status')->default(0);


            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('otvet_na_analizs');
    }
};
