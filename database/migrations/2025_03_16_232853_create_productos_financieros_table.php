<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductosFinancierosTable extends Migration
{
    public function up()
    {
        Schema::create('productos_financieros', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->enum('tipo', ['cuenta_ahorro', 'cuenta_corriente', 'tarjeta_debito', 'tarjeta_credito', 'prestamo', 'seguro']);
            $table->string('numero_referencia')->unique();
            $table->date('fecha_apertura');
            $table->decimal('monto_apertura', 10, 2)->nullable();
            $table->date('fecha_cierre')->nullable();
            $table->string('beneficiarios')->nullable();
            $table->decimal('limite_monto', 10, 2)->nullable();
            $table->string('tipo_red')->nullable();
            $table->string('categoria')->nullable();
            $table->decimal('tasa_interes', 5, 2)->nullable();
            $table->decimal('costo_membresia', 10, 2)->nullable();
            $table->integer('plazo_pago_meses')->nullable();
            $table->decimal('cuota', 10, 2)->nullable();
            $table->date('fecha_limite_pago')->nullable();
            $table->decimal('cuota_seguro', 10, 2)->nullable();
            $table->decimal('monto_asegurado', 10, 2)->nullable();
            $table->date('fecha_contratacion')->nullable();
            $table->date('fecha_finalizacion')->nullable();
            $table->string('tipo_seguro')->nullable();
            $table->decimal('renta_diaria_hospitalizacion', 10, 2)->nullable();
            $table->text('causas_aplicables')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('productos_financieros');
    }
}