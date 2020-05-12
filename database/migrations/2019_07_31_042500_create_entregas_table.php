<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntregasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entregas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_provedor')->unsigned();
            $table->integer('cantidadM')->unsigned();
            $table->integer('cantidadT')->unsigned();
            $table->integer('total')->unsigned();
            $table->float('precio')->unsigned();
            $table->float('valor')->unsigned();
            $table->boolean('condicion')->default(1);
            $table->timestamps();

            $table->foreign('id_provedor')->references('id')->on('proveedores');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entregas');
    }
}
