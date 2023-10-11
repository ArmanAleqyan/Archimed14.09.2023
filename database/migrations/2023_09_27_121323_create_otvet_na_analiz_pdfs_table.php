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
        Schema::create('otvet_na_analiz_pdfs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('otvet_id')->nullable();
            $table->string('FileName')->nullable();
            $table->longText('PDF')->nullable();
            $table->foreign('otvet_id')->references('id')->on('otvet_na_analizs')->onDelete('cascade');
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
        Schema::dropIfExists('otvet_na_analiz_pdfs');
    }
};
