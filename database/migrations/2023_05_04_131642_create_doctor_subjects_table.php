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
        Schema::create('doctor_subjects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('doctor_id')->nullable();

            $table->foreign('doctor_id')->references('id')->on('doctor_lists') ->onDelete('cascade');
            $table->string('PL_SUBJ_ID')->nullable();
            $table->string('subj_name')->nullable();
            $table->string('subj_arch')->nullable();
            $table->string('VILLE')->nullable();
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
        Schema::dropIfExists('doctor_subjects');
    }
};
