<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ACOEMPRENDEDORES</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-900 text-white">
    <div class="container mx-auto p-4">
        <!-- Barra de navegación -->
        <nav class="bg-gray-800 p-4 mb-6 rounded">
            <div class="flex justify-between items-center">
                <a href="{{ url('/') }}" class="text-xl font-bold text-white">ACOEMPRENDEDORES</a>
                <div class="space-x-4">
                    <a href="{{ route('empleados.index') }}" class="text-gray-300 hover:text-white">Empleados</a>
                    <a href="{{ route('clientes.index') }}" class="text-gray-300 hover:text-white">Clientes</a>
                    <a href="{{ route('productos-financieros.index') }}" class="text-gray-300 hover:text-white">Productos Financieros</a>
                    <a href="{{ route('transacciones.index') }}" class="text-gray-300 hover:text-white">Transacciones</a>
                </div>
            </div>
        </nav>

        <!-- Contenido principal -->
        <main>
            @yield('content')
        </main>
    </div>
</body>
</html>