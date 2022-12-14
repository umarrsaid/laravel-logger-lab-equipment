<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SatUkuran extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sat_ukuran', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode_satuan');
            $table->string('nama_sat');
            $table->string('simbol');
            $table->tinyInteger('id_machine');
            $table->softDeletes();
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
        Schema::dropIfExists('sat_ukuran');
    }
}
