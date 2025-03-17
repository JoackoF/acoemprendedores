@extends('layouts.app')

@section('content')
<div class="bg-gray-800 p-6 rounded-lg shadow-lg">
    <h1 class="text-2xl font-bold mb-4">Crear Cliente</h1>
    <form action="{{ route('clientes.store') }}" method="POST" class="space-y-4">
        @csrf
        <!-- Nombre Completo -->
        <div>
            <label for="nombre_completo" class="block text-gray-300">Nombre Completo</label>
            <input type="text" name="nombre_completo" id="nombre_completo" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-white">
        </div>
        <!-- Documento de Identidad -->
        <div>
            <label for="documento_identidad" class="block text-gray-300">Documento de Identidad</label>
            <input type="text" name="documento_identidad" id="documento_identidad" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-white">
        </div>
        <!-- Fecha de Nacimiento -->
        <div>
            <label for="fecha_nacimiento" class="block text-gray-300">Fecha de Nacimiento</label>
            <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-white">
        </div>
        <!-- Edad -->
        <div>
            <label for="edad" class="block text-gray-300">Edad</label>
            <input type="number" name="edad" id="edad" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-white">
        </div>
        <!-- Dirección Completa -->
        <div>
            <label for="direccion_completa" class="block text-gray-300">Dirección Completa</label>
            <input type="text" name="direccion_completa" id="direccion_completa" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-white">
        </div>
        <!-- Estado Familiar -->
        <div>
            <label for="estado_familiar" class="block text-gray-300">Estado Familiar</label>
            <input type="text" name="estado_familiar" id="estado_familiar" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-white">
        </div>
        <!-- Profesión -->
        <div>
            <label for="profesion" class="block text-gray-300">Profesión</label>
            <input type="text" name="profesion" id="profesion" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-white">
        </div>
        <!-- Correo Electrónico -->
        <div>
            <label for="correo_electronico" class="block text-gray-300">Correo Electrónico</label>
            <input type="email" name="correo_electronico" id="correo_electronico" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-white">
        </div>
        <!-- Teléfono -->
        <div>
            <label for="telefono" class="block text-gray-300">Teléfono</label>
            <input type="text" name="telefono" id="telefono" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-white">
        </div>
        <!-- Lugar de Trabajo -->
        <div>
            <label for="lugar_trabajo" class="block text-gray-300">Lugar de Trabajo</label>
            <input type="text" name="lugar_trabajo" id="lugar_trabajo" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-white">
        </div>
        <!-- Dirección de Trabajo -->
        <div>
            <label for="direccion_trabajo" class="block text-gray-300">Dirección de Trabajo</label>
            <input type="text" name="direccion_trabajo" id="direccion_trabajo" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-white">
        </div>
        <!-- Salario Mensual -->
        <div>
            <label for="salario_mensual" class="block text-gray-300">Salario Mensual</label>
            <input type="number" step="0.01" name="salario_mensual" id="salario_mensual" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-white">
        </div>
        <!-- Otros Ingresos -->
        <div>
            <label for="otros_ingresos" class="block text-gray-300">Otros Ingresos</label>
            <input type="number" step="0.01" name="otros_ingresos" id="otros_ingresos" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-white">
        </div>
        <!-- Botón de Guardar -->
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Guardar</button>
    </form>
</div>
@endsection