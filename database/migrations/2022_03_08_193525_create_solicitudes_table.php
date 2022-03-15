<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSolicitudesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicitudes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_usuario');
            $table->integer('id_categoria');
            $table->integer('id_estado');
            $table->integer('id_tecnico')->nullable();
            $table->string('descripcion', 1000);
            $table->date('fecha_cita');
            $table->string('imagen')->nullable();
            $table->string('comentario')->nullable();
            $table->date('fecha_listo')->nullable();
            $table->date('fecha_real')->nullable();
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
        Schema::dropIfExists('solicitudes');
    }
}
