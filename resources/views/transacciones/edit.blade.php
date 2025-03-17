@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-4">Editar Transacción</h1>
    <form action="{{ route('transacciones.update', $transaccion->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label for="codigo_transaccion" class="block text-gray-700">Código de Transacción</label>
            <input type="text" name="codigo_transaccion" id="codigo_transaccion" value="{{ $transaccion->codigo_transaccion }}" class="w-full px-4 py-2 border rounded">
        </div>
        <div class="mb-4">
            <label for="producto_financiero_id" class="block text-gray-700">Producto Financiero</label>
            <select name="producto_financiero_id" id="producto_financiero_id" class="w-full px-4 py-2 border rounded">
                @foreach ($productosFinancieros as $producto)
                    <option value="{{ $producto->id }}" {{ $transaccion->producto_financiero_id == $producto->id ? 'selected' : '' }}>{{ $producto->numero_referencia }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label for="empleado_id" class="block text-gray-700">Empleado</label>
            <select name="empleado_id" id="empleado_id" class="w-full px-4 py-2 border rounded">
                @foreach ($empleados as $empleado)
                    <option value="{{ $empleado->id }}" {{ $transaccion->empleado_id == $empleado->id ? 'selected' : '' }}>{{ $empleado->nombre_completo }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label for="monto" class="block text-gray-700">Monto</label>
            <input type="number" step="0.01" name="monto" id="monto" value="{{ $transaccion->monto }}" class="w-full px-4 py-2 border rounded">
        </div>
        <div class="mb-4">
            <label for="fecha_transaccion" class="block text-gray-700">Fecha de Transacción</label>
            <input type="date" name="fecha_transaccion" id="fecha_transaccion" value="{{ $transaccion->fecha_transaccion }}" class="w-full px-4 py-2 border rounded">
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Actualizar</button>
    </form>
</div>
@endsection