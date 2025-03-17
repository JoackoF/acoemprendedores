@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-4">Lista de Productos Financieros</h1>
    <a href="{{ route('productos-financieros.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">Crear Producto Financiero</a>
    <table class="min-w-full bg-white">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">Número de Referencia</th>
                <th class="py-2 px-4 border-b">Tipo</th>
                <th class="py-2 px-4 border-b">Cliente</th>
                <th class="py-2 px-4 border-b">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($productosFinancieros as $producto)
                <tr>
                    <td class="py-2 px-4 border-b">{{ $producto->numero_referencia }}</td>
                    <td class="py-2 px-4 border-b">{{ $producto->tipo }}</td>
                    <td class="py-2 px-4 border-b">{{ $producto->cliente->nombre_completo }}</td>
                    <td class="py-2 px-4 border-b">
                        <a href="{{ route('productos-financieros.show', $producto->id) }}" class="text-blue-500">Ver</a>
                        <a href="{{ route('productos-financieros.edit', $producto->id) }}" class="text-yellow-500 ml-2">Editar</a>
                        <form action="{{ route('productos-financieros.destroy', $producto->id) }}" method="POST" class="inline">
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