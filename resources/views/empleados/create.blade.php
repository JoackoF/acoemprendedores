@extends('layouts.app')

@section('content')
<div class="bg-gray-800 p-6 rounded-lg shadow-lg">
    <h1 class="text-2xl font-bold mb-4">Crear Empleado</h1>
    <form action="{{ route('empleados.store') }}" method="POST" class="space-y-4">
        @csrf
        <!-- Código de Empleado -->
        <div>
            <label for="codigo_empleado" class="block text-gray-300">Código de Empleado</label>
            <input type="text" name="codigo_empleado" id="codigo_empleado" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-white">
        </div>
        <!-- Nombre Completo -->
        <div>
            <label for="nombre_completo" class="block text-gray-300">Nombre Completo</label>
            <input type="text" name="nombre_completo" id="nombre_completo" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-white">
        </div>
        <!-- Estado Familiar -->
        <div>
            <label for="estado_familiar" class="block text-gray-300">Estado Familiar</label>
            <input type="text" name="estado_familiar" id="estado_familiar" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-white">
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
        <!-- Puesto -->
        <div>
            <label for="puesto" class="block text-gray-300">Puesto</label>
            <input type="text" name="puesto" id="puesto" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-white">
        </div>
        <!-- Departamento -->
        <div>
            <label for="departamento" class="block text-gray-300">Departamento</label>
            <select name="departamento" id="departamento" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-white">
                <option value="finanzas">Finanzas</option>
                <option value="atencion_al_cliente">Atención al Cliente</option>
                <option value="gerencia">Gerencia</option>
                <option value="servicios_varios">Servicios Varios</option>
                <option value="seguridad">Seguridad</option>
            </select>
        </div>
        <!-- Sueldo -->
        <div>
            <label for="sueldo" class="block text-gray-300">Sueldo</label>
            <input type="number" step="0.01" name="sueldo" id="sueldo" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-white">
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
        <!-- Botón de Guardar -->
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Guardar</button>
    </form>
</div>
@endsection