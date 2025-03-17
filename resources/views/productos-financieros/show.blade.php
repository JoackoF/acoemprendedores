@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-4">Detalles del Producto Financiero</h1>
    <div class="bg-white p-6 rounded shadow">
        <p><strong>Número de Referencia:</strong> {{ $producto->numero_referencia }}</p>
        <p><strong>Tipo:</strong> {{ $producto->tipo }}</p>
        <p><strong>Cliente:</strong> {{ $producto->cliente->nombre_completo }}</p>
        <!-- Agrega más campos según sea necesario -->
        <a href="{{ route('productos-financieros.edit', $producto->id) }}" class="bg-yellow-500 text-white px-4 py-2 rounded mt-4 inline-block">Editar</a>
        <form action="{{ route('productos-financieros.destroy', $producto->id) }}" method="POST" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded mt-4">Eliminar</button>
        </form>
    </div>
</div>
@endsection