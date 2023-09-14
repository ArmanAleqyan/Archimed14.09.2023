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
        Schema::create('start_infos', function (Blueprint $table) {
            $table->id();
            $table->string('headerOne')->nullable();
            $table->longText('textOne')->nullable();

            $table->string('headerTwo')->nullable();
            $table->longText('textTwo')->nullable();

            $table->string('headerThree')->nullable();
            $table->longText('textThree')->nullable();

            $table->string('headerFour')->nullable();
            $table->longText('textFour')->nullable();

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
        Schema::dropIfExists('start_infos');
    }
};
