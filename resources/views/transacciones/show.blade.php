@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-4">Detalles de la Transacción</h1>
    <div class="bg-white p-6 rounded shadow">
        <p><strong>Código de Transacción:</strong> {{ $transaccion->codigo_transaccion }}</p>
        <p><strong>Producto Financiero:</strong> {{ $transaccion->productoFinanciero->numero_referencia }}</p>
        <p><strong>Empleado:</strong> {{ $transaccion->empleado->nombre_completo }}</p>
        <p><strong>Monto:</strong> {{ $transaccion->monto }}</p>
        <p><strong>Fecha de Transacción:</strong> {{ $transaccion->fecha_transaccion }}</p>
        <a href="{{ route('transacciones.edit', $transaccion->id) }}" class="bg-yellow-500 text-white px-4 py-2 rounded mt-4 inline-block">Editar</a>
        <form action="{{ route('transacciones.destroy', $transaccion->id) }}" method="POST" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded mt-4">Eliminar</button>
        </form>
    </div>
</div>
@endsection