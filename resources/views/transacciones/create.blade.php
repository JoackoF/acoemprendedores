@extends('layouts.app')

@section('content')
<div class="bg-gray-800 p-6 rounded-lg shadow-lg">
    <h1 class="text-2xl font-bold mb-4">Crear Transacción</h1>
    <form action="{{ route('transacciones.store') }}" method="POST" class="space-y-4">
        @csrf
        <!-- Código de Transacción -->
        <div>
            <label for="codigo_transaccion" class="block text-gray-300">Código de Transacción</label>
            <input type="text" name="codigo_transaccion" id="codigo_transaccion" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-white">
        </div>
        <!-- Producto Financiero -->
        <div>
            <label for="producto_financiero_id" class="block text-gray-300">Producto Financiero</label>
            <select name="producto_financiero_id" id="producto_financiero_id" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-white">
                @foreach ($productosFinancieros as $producto)
                    <option value="{{ $producto->id }}">{{ $producto->numero_referencia }}</option>
                @endforeach
            </select>
        </div>
        <!-- Empleado -->
        <div>
            <label for="empleado_id" class="block text-gray-300">Empleado</label>
            <select name="empleado_id" id="empleado_id" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-white">
                @foreach ($empleados as $empleado)
                    <option value="{{ $empleado->id }}">{{ $empleado->nombre_completo }}</option>
                @endforeach
            </select>
        </div>
        <!-- Monto -->
        <div>
            <label for="monto" class="block text-gray-300">Monto</label>
            <input type="number" step="0.01" name="monto" id="monto" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-white">
        </div>
        <!-- Fecha de Transacción -->
        <div>
            <label for="fecha_transaccion" class="block text-gray-300">Fecha de Transacción</label>
            <input type="date" name="fecha_transaccion" id="fecha_transaccion" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-white">
        </div>
        <!-- Botón de Guardar -->
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Guardar</button>
    </form>
</div>
@endsection