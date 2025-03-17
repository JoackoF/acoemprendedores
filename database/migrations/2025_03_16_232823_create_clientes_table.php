<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientesTable extends Migration
{
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_completo');
            $table->string('documento_identidad')->unique();
            $table->date('fecha_nacimiento');
            $table->integer('edad');
            $table->string('direccion_completa');
            $table->string('estado_familiar');
            $table->string('profesion');
            $table->string('correo_electronico')->unique();
            $table->string('telefono');
            $table->string('lugar_trabajo');
            $table->string('direccion_trabajo');
            $table->decimal('salario_mensual', 10, 2);
            $table->decimal('otros_ingresos', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('clientes');
    }
}