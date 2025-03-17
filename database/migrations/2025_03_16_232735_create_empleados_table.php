<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpleadosTable extends Migration
{
    public function up()
    {
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_empleado')->unique();
            $table->string('nombre_completo');
            $table->string('estado_familiar');
            $table->string('documento_identidad')->unique();
            $table->date('fecha_nacimiento');
            $table->integer('edad');
            $table->string('direccion_completa');
            $table->string('puesto');
            $table->string('departamento');
            $table->decimal('sueldo', 10, 2);
            $table->string('profesion');
            $table->string('correo_electronico')->unique();
            $table->string('telefono');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('empleados');
    }
}