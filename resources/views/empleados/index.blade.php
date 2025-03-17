@extends('layouts.app')

@section('content')
<div class="bg-gray-800 p-6 rounded-lg shadow-lg">
    <h1 class="text-2xl font-bold mb-4">Lista de Empleados</h1>
    <a href="{{ route('empleados.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block hover:bg-blue-600">Crear Empleado</a>
    <table class="min-w-full bg-gray-700 rounded-lg overflow-hidden">
        <thead class="bg-gray-600">
            <tr>
                <th class="py-3 px-4 text-left">Código</th>
                <th class="py-3 px-4 text-left">Nombre</th>
                <th class="py-3 px-4 text-left">Documento</th>
                <th class="py-3 px-4 text-left">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($empleados as $empleado)
                <tr class="hover:bg-gray-600">
                    <td class="py-3 px-4 border-b border-gray-600">{{ $empleado->codigo_empleado }}</td>
                    <td class="py-3 px-4 border-b border-gray-600">{{ $empleado->nombre_completo }}</td>
                    <td class="py-3 px-4 border-b border-gray-600">{{ $empleado->documento_identidad }}</td>
                    <td class="py-3 px-4 border-b border-gray-600">
                        <a href="{{ route('empleados.show', $empleado->id) }}" class="text-blue-400 hover:text-blue-300">Ver</a>
                        <a href="{{ route('empleados.edit', $empleado->id) }}" class="text-yellow-400 hover:text-yellow-300 ml-2">Editar</a>
                        <form action="{{ route('empleados.destroy', $empleado->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-300 ml-2">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection