@extends('layouts.app')

@section('content')
<div class="bg-gray-800 p-6 rounded-lg shadow-lg">
    <h1 class="text-2xl font-bold mb-4">Detalles del Cliente</h1>
    <div class="space-y-4">
        <p><strong>Nombre:</strong> <span class="text-gray-300">{{ $cliente->nombre_completo }}</span></p>
        <p><strong>Documento:</strong> <span class="text-gray-300">{{ $cliente->documento_identidad }}</span></p>
        <p><strong>Fecha de Nacimiento:</strong> <span class="text-gray-300">{{ $cliente->fecha_nacimiento }}</span></p>
        <!-- Agrega más campos según sea necesario -->
        <div class="flex space-x-4">
            <a href="{{ route('clientes.edit', $cliente->id) }}" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Editar</a>
            <form action="{{ route('clientes.destroy', $cliente->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Eliminar</button>
            </form>
        </div>
    </div>
</div>
@endsection