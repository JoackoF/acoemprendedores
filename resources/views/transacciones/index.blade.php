@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-4">Lista de Transacciones</h1>
    <a href="{{ route('transacciones.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">Crear Transacción</a>
    <table class="min-w-full bg-white">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">Código de Transacción</th>
                <th class="py-2 px-4 border-b">Producto Financiero</th>
                <th class="py-2 px-4 border-b">Empleado</th>
                <th class="py-2 px-4 border-b">Monto</th>
                <th class="py-2 px-4 border-b">Fecha</th>
                <th class="py-2 px-4 border-b">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transacciones as $transaccion)
                <tr>
                    <td class="py-2 px-4 border-b">{{ $transaccion->codigo_transaccion }}</td>
                    <td class="py-2 px-4 border-b">{{ $transaccion->productoFinanciero->numero_referencia }}</td>
                    <td class="py-2 px-4 border-b">{{ $transaccion->empleado->nombre_completo }}</td>
                    <td class="py-2 px-4 border-b">{{ $transaccion->monto }}</td>
                    <td class="py-2 px-4 border-b">{{ $transaccion->fecha_transaccion }}</td>
                    <td class="py-2 px-4 border-b">
                        <a href="{{ route('transacciones.show', $transaccion->id) }}" class="text-blue-500">Ver</a>
                        <a href="{{ route('transacciones.edit', $transaccion->id) }}" class="text-yellow-500 ml-2">Editar</a>
                        <form action="{{ route('transacciones.destroy', $transaccion->id) }}" method="POST" class="inline">
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