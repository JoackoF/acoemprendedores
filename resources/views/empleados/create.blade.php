@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-4">Crear Empleado</h1>
    <form action="{{ route('empleados.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="codigo_empleado" class="block text-gray-700">Código de Empleado</label>
            <input type="text" name="codigo_empleado" id="codigo_empleado" class="w-full px-4 py-2 border rounded">
        </div>
        <div class="mb-4">
            <label for="nombre_completo" class="block text-gray-700">Nombre Completo</label>
            <input type="text" name="nombre_completo" id="nombre_completo" class="w-full px-4 py-2 border rounded">
        </div>
        <!-- Agrega más campos según sea necesario -->
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Guardar</button>
    </form>
</div>
@endsection