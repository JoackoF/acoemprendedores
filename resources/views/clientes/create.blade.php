@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-4">Crear Cliente</h1>
    <form action="{{ route('clientes.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="nombre_completo" class="block text-gray-700">Nombre Completo</label>
            <input type="text" name="nombre_completo" id="nombre_completo" class="w-full px-4 py-2 border rounded">
        </div>
        <div class="mb-4">
            <label for="documento_identidad" class="block text-gray-700">Documento de Identidad</label>
            <input type="text" name="documento_identidad" id="documento_identidad" class="w-full px-4 py-2 border rounded">
        </div>
        <div class="mb-4">
            <label for="fecha_nacimiento" class="block text-gray-700">Fecha de Nacimiento</label>
            <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" class="w-full px-4 py-2 border rounded">
        </div>
        <!-- Agrega más campos según sea necesario -->
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Guardar</button>
    </form>
</div>
@endsection