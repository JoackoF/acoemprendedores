@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-4">Lista de Empleados</h1>
    <a href="{{ route('empleados.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">Crear Empleado</a>
    <table class="min-w-full bg-white">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">Código</th>
                <th class="py-2 px-4 border-b">Nombre</th>
                <th class="py-2 px-4 border-b">Documento</th>
                <th class="py-2 px-4 border-b">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($empleados as $empleado)
                <tr>
                    <td class="py-2 px-4 border-b">{{ $empleado->codigo_empleado }}</td>
                    <td class="py-2 px-4 border-b">{{ $empleado->nombre_completo }}</td>
                    <td class="py-2 px-4 border-b">{{ $empleado->documento_identidad }}</td>
                    <td class="py-2 px-4 border-b">
                        <a href="{{ route('empleados.show', $empleado->id) }}" class="text-blue-500">Ver</a>
                        <a href="{{ route('empleados.edit', $empleado->id) }}" class="text-yellow-500 ml-2">Editar</a>
                        <form action="{{ route('empleados.destroy', $empleado->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 ml-2">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection