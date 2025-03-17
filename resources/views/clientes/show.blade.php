@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-4">Detalles del Cliente</h1>
    <div class="bg-white p-6 rounded shadow">
        <p><strong>Nombre:</strong> {{ $cliente->nombre_completo }}</p>
        <p><strong>Documento:</strong> {{ $cliente->documento_identidad }}</p>
        <p><strong>Fecha de Nacimiento:</strong> {{ $cliente->fecha_nacimiento }}</p>
        <!-- Agrega más campos según sea necesario -->
        <a href="{{ route('clientes.edit', $cliente->id) }}" class="bg-yellow-500 text-white px-4 py-2 rounded mt-4 inline-block">Editar</a>
        <form action="{{ route('clientes.destroy', $cliente->id) }}" method="POST" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded mt-4">Eliminar</button>
        </form>
    </div>
</div>
@endsection