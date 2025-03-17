@extends('layouts.app')

@section('content')
<div class="bg-gray-800 p-6 rounded-lg shadow-lg">
    <h1 class="text-2xl font-bold mb-4">Detalles del Empleado</h1>
    <div class="space-y-4">
        <p><strong>Código:</strong> <span class="text-gray-300">{{ $empleado->codigo_empleado }}</span></p>
        <p><strong>Nombre:</strong> <span class="text-gray-300">{{ $empleado->nombre_completo }}</span></p>
        <p><strong>Documento:</strong> <span class="text-gray-300">{{ $empleado->documento_identidad }}</span></p>
        <!-- Agrega más campos según sea necesario -->
        <div class="flex space-x-4">
            <a href="{{ route('empleados.edit', $empleado->id) }}" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Editar</a>
            <form action="{{ route('empleados.destroy', $empleado->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Eliminar</button>
            </form>
        </div>
    </div>
</div>
@endsection