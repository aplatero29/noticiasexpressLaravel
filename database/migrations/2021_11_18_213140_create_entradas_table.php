<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntradasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entradas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('categoria_id')->references('id')->on('categorias')->onDelete('cascade');
            $table->string('titulo');
            $table->string('imagen');
            $table->mediumText('descripcion');
            $table->timestamps();
        });

        /*TODO: Acciones En Update ; Delete 
        UPDATE: Categorias-CASCADE ; Delete: Categorias-CASCADE 
        UPDATE: Usuarios-CASCADE   ; Delete: Usuarios-NO ACTION */
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entradas');
    }
}
