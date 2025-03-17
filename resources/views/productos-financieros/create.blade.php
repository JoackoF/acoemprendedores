@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-4">Crear Producto Financiero</h1>
    <form action="{{ route('productos-financieros.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="cliente_id" class="block text-gray-700">Cliente</label>
            <select name="cliente_id" id="cliente_id" class="w-full px-4 py-2 border rounded">
                @foreach ($clientes as $cliente)
                    <option value="{{ $cliente->id }}">{{ $cliente->nombre_completo }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label for="tipo" class="block text-gray-700">Tipo</label>
            <select name="tipo" id="tipo" class="w-full px-4 py-2 border rounded">
                <option value="cuenta_ahorro">Cuenta de Ahorro</option>
                <option value="cuenta_corriente">Cuenta Corriente</option>
                <option value="tarjeta_debito">Tarjeta de Débito</option>
                <option value="tarjeta_credito">Tarjeta de Crédito</option>
                <option value="prestamo">Préstamo</option>
                <option value="seguro">Seguro</option>
            </select>
        </div>
        <!-- Agrega más campos según sea necesario -->
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Guardar</button>
    </form>
</div>
@endsection