@extends('layouts.app')

@section('content')
<div class="bg-gray-800 p-6 rounded-lg shadow-lg">
    <h1 class="text-2xl font-bold mb-4">Editar Empleado</h1>
    <form action="{{ route('empleados.update', $empleado->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label for="codigo_empleado" class="block text-gray-300">Código de Empleado</label>
            <input type="text" name="codigo_empleado" id="codigo_empleado" value="{{ $empleado->codigo_empleado }}" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-white">
        </div>
        <div>
            <label for="nombre_completo" class="block text-gray-300">Nombre Completo</label>
            <input type="text" name="nombre_completo" id="nombre_completo" value="{{ $empleado->nombre_completo }}" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-white">
        </div>
        <div>
            <label for="documento_identidad" class="block text-gray-300">Documento de Identidad</label>
            <input type="text" name="documento_identidad" id="documento_identidad" value="{{ $empleado->documento_identidad }}" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-white">
        </div>
        <!-- Agrega más campos según sea necesario -->
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Actualizar</button>
    </form>
</div>
@endsection