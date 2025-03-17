@extends('layouts.app')

@section('content')
<div class="bg-gray-800 p-6 rounded-lg shadow-lg">
    <h1 class="text-2xl font-bold mb-4">Crear Producto Financiero</h1>
    <form action="{{ route('productos-financieros.store') }}" method="POST" class="space-y-4">
        @csrf
        <!-- Cliente -->
        <div>
            <label for="cliente_id" class="block text-gray-300">Cliente</label>
            <select name="cliente_id" id="cliente_id" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-white">
                @foreach ($clientes as $cliente)
                    <option value="{{ $cliente->id }}">{{ $cliente->nombre_completo }}</option>
                @endforeach
            </select>
        </div>
        <!-- Tipo de Producto -->
        <div>
            <label for="tipo" class="block text-gray-300">Tipo de Producto</label>
            <select name="tipo" id="tipo" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-white">
                <option value="cuenta_ahorro">Cuenta de Ahorro</option>
                <option value="cuenta_corriente">Cuenta Corriente</option>
                <option value="tarjeta_debito">Tarjeta de Débito</option>
                <option value="tarjeta_credito">Tarjeta de Crédito</option>
                <option value="prestamo">Préstamo</option>
                <option value="seguro">Seguro</option>
            </select>
        </div>
        <!-- Número de Referencia -->
        <div>
            <label for="numero_referencia" class="block text-gray-300">Número de Referencia</label>
            <input type="text" name="numero_referencia" id="numero_referencia" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-white">
        </div>
        <!-- Fecha de Apertura -->
        <div>
            <label for="fecha_apertura" class="block text-gray-300">Fecha de Apertura</label>
            <input type="date" name="fecha_apertura" id="fecha_apertura" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-white">
        </div>
        <!-- Monto de Apertura -->
        <div>
            <label for="monto_apertura" class="block text-gray-300">Monto de Apertura</label>
            <input type="number" step="0.01" name="monto_apertura" id="monto_apertura" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-white">
        </div>
        <!-- Fecha de Cierre -->
        <div>
            <label for="fecha_cierre" class="block text-gray-300">Fecha de Cierre</label>
            <input type="date" name="fecha_cierre" id="fecha_cierre" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-white">
        </div>
        <!-- Beneficiarios -->
        <div>
            <label for="beneficiarios" class="block text-gray-300">Beneficiarios</label>
            <input type="text" name="beneficiarios" id="beneficiarios" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-white">
        </div>
        <!-- Límite de Monto (para tarjetas) -->
        <div>
            <label for="limite_monto" class="block text-gray-300">Límite de Monto</label>
            <input type="number" step="0.01" name="limite_monto" id="limite_monto" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-white">
        </div>
        <!-- Tipo de Red (para tarjetas) -->
        <div>
            <label for="tipo_red" class="block text-gray-300">Tipo de Red</label>
            <select name="tipo_red" id="tipo_red" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-white">
                <option value="Visa">Visa</option>
                <option value="MasterCard">MasterCard</option>
            </select>
        </div>
        <!-- Categoría (para tarjetas) -->
        <div>
            <label for="categoria" class="block text-gray-300">Categoría</label>
            <select name="categoria" id="categoria" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-white">
                <option value="Clásica">Clásica</option>
                <option value="Infinite">Infinite</option>
                <option value="Oro">Oro</option>
                <option value="Platinum">Platinum</option>
                <option value="Empresarial">Empresarial</option>
            </select>
        </div>
        <!-- Tasa de Interés (para préstamos) -->
        <div>
            <label for="tasa_interes" class="block text-gray-300">Tasa de Interés</label>
            <input type="number" step="0.01" name="tasa_interes" id="tasa_interes" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-white">
        </div>
        <!-- Costo de Membresía (para tarjetas) -->
        <div>
            <label for="costo_membresia" class="block text-gray-300">Costo de Membresía</label>
            <input type="number" step="0.01" name="costo_membresia" id="costo_membresia" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-white">
        </div>
        <!-- Plazo de Pago (para préstamos) -->
        <div>
            <label for="plazo_pago_meses" class="block text-gray-300">Plazo de Pago (meses)</label>
            <input type="number" name="plazo_pago_meses" id="plazo_pago_meses" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-white">
        </div>
        <!-- Cuota (para préstamos) -->
        <div>
            <label for="cuota" class="block text-gray-300">Cuota</label>
            <input type="number" step="0.01" name="cuota" id="cuota" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-white">
        </div>
        <!-- Fecha Límite de Pago (para préstamos) -->
        <div>
            <label for="fecha_limite_pago" class="block text-gray-300">Fecha Límite de Pago</label>
            <input type="date" name="fecha_limite_pago" id="fecha_limite_pago" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-white">
        </div>
        <!-- Cuota de Seguro (para seguros) -->
        <div>
            <label for="cuota_seguro" class="block text-gray-300">Cuota de Seguro</label>
            <input type="number" step="0.01" name="cuota_seguro" id="cuota_seguro" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-white">
        </div>
        <!-- Monto Asegurado (para seguros) -->
        <div>
            <label for="monto_asegurado" class="block text-gray-300">Monto Asegurado</label>
            <input type="number" step="0.01" name="monto_asegurado" id="monto_asegurado" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-white">
        </div>
        <!-- Fecha de Contratación (para seguros) -->
        <div>
            <label for="fecha_contratacion" class="block text-gray-300">Fecha de Contratación</label>
            <input type="date" name="fecha_contratacion" id="fecha_contratacion" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-white">
        </div>
        <!-- Fecha de Finalización (para seguros) -->
        <div>
            <label for="fecha_finalizacion" class="block text-gray-300">Fecha de Finalización</label>
            <input type="date" name="fecha_finalizacion" id="fecha_finalizacion" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-white">
        </div>
        <!-- Tipo de Seguro -->
        <div>
            <label for="tipo_seguro" class="block text-gray-300">Tipo de Seguro</label>
            <select name="tipo_seguro" id="tipo_seguro" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-white">
                <option value="vida">Vida</option>
                <option value="salud">Salud</option>
                <option value="asistencia">Asistencia</option>
            </select>
        </div>
        <!-- Renta Diaria de Hospitalización (para seguros de salud) -->
        <div>
            <label for="renta_diaria_hospitalizacion" class="block text-gray-300">Renta Diaria de Hospitalización</label>
            <input type="number" step="0.01" name="renta_diaria_hospitalizacion" id="renta_diaria_hospitalizacion" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-white">
        </div>
        <!-- Causas Aplicables (para seguros) -->
        <div>
            <label for="causas_aplicables" class="block text-gray-300">Causas Aplicables</label>
            <textarea name="causas_aplicables" id="causas_aplicables" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-white"></textarea>
        </div>
        <!-- Botón de Guardar -->
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Guardar</button>
    </form>
</div>
@endsection