<?php
session_start();

// Verificar que el usuario es un empleado
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'empleado') {
    header('Location: login.php');
    exit();
}

require 'conexion.php';

// Procesar formulario para registrar un cliente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registrar_cliente'])) {
    $nombre_completo = $_POST['nombre_completo'];
    $documento_identidad = $_POST['documento_identidad'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];

    $stmt = $pdo->prepare("INSERT INTO clientes (nombre_completo, documento_identidad, correo, telefono) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nombre_completo, $documento_identidad, $correo, $telefono]);

    header('Location: dashboard_empleado.php');
    exit();
}

// Procesar formulario para asignar un producto financiero
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['asignar_producto'])) {
    $id_cliente = $_POST['id_cliente'];
    $tipo_producto = $_POST['tipo_producto'];
    $detalle_producto = $_POST['detalle_producto'];

    $stmt = $pdo->prepare("INSERT INTO productos_financieros (id_cliente, tipo_producto, detalle_producto) VALUES (?, ?, ?)");
    $stmt->execute([$id_cliente, $tipo_producto, $detalle_producto]);

    header('Location: dashboard_empleado.php');
    exit();
}

// Procesar formulario para registrar una transacción
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registrar_transaccion'])) {
    $id_producto = $_POST['id_producto'];
    $monto = $_POST['monto'];

    $stmt = $pdo->prepare("INSERT INTO transacciones (id_producto, id_empleado, monto) VALUES (?, ?, ?)");
    $stmt->execute([$id_producto, $_SESSION['usuario_id'], $monto]);

    header('Location: dashboard_empleado.php');
    exit();
}

// Obtener estadísticas básicas
$totalClientes = $pdo->query("SELECT COUNT(*) as total FROM clientes")->fetch(PDO::FETCH_ASSOC)['total'];
$totalProductos = $pdo->query("SELECT COUNT(*) as total FROM productos_financieros")->fetch(PDO::FETCH_ASSOC)['total'];
$totalTransacciones = $pdo->query("SELECT COUNT(*) as total FROM transacciones")->fetch(PDO::FETCH_ASSOC)['total'];

// Obtener lista de clientes
$clientes = $pdo->query("SELECT id_cliente, nombre_completo FROM clientes")->fetchAll(PDO::FETCH_ASSOC);

// Obtener lista de productos financieros
$productos = $pdo->query("SELECT pf.id_producto, pf.tipo_producto, c.nombre_completo 
                          FROM productos_financieros pf
                          JOIN clientes c ON pf.id_cliente = c.id_cliente")->fetchAll(PDO::FETCH_ASSOC);

// Obtener últimas transacciones
$ultimasTransacciones = $pdo->query("SELECT t.id_transaccion, t.monto, t.fecha_transaccion, c.nombre_completo 
                                     FROM transacciones t
                                     JOIN productos_financieros pf ON t.id_producto = pf.id_producto
                                     JOIN clientes c ON pf.id_cliente = c.id_cliente
                                     ORDER BY t.fecha_transaccion DESC
                                     LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Empleado - ACOEMPRENDEDORES</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex">
        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>

        <!-- Main content -->
        <div class="flex-1 p-6">
            <h1 class="text-3xl font-semibold mb-6">Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre']); ?></h1>

            <div class="mb-6">
                <a href="logout.php" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600">Cerrar Sesión</a>
            </div>

            <!-- Estadísticas básicas -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h2 class="text-xl font-semibold mb-2">Clientes</h2>
                    <p class="text-3xl font-bold"><?php echo $totalClientes; ?></p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h2 class="text-xl font-semibold mb-2">Productos Financieros</h2>
                    <p class="text-3xl font-bold"><?php echo $totalProductos; ?></p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h2 class="text-xl font-semibold mb-2">Transacciones</h2>
                    <p class="text-3xl font-bold"><?php echo $totalTransacciones; ?></p>
                </div>
            </div>

            <!-- Últimas transacciones -->
            <div class="bg-white p-6 rounded-lg shadow-lg mb-8">
                <h2 class="text-xl font-semibold mb-4">Últimas Transacciones</h2>
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th class="px-6 py-4 text-left">ID Transacción</th>
                            <th class="px-6 py-4 text-left">Cliente</th>
                            <th class="px-6 py-4 text-left">Monto</th>
                            <th class="px-6 py-4 text-left">Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ultimasTransacciones as $transaccion): ?>
                            <tr>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($transaccion['id_transaccion']); ?></td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($transaccion['nombre_completo']); ?></td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($transaccion['monto']); ?></td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($transaccion['fecha_transaccion']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Botones para mostrar formularios -->
            <div class="mb-6 space-x-4">
                <button onclick="mostrarFormulario('formularioCliente')" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                    Registrar Cliente
                </button>
                <button onclick="mostrarFormulario('formularioProducto')" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                    Asignar Producto Financiero
                </button>
                <button onclick="mostrarFormulario('formularioTransaccion')" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                    Registrar Transacción
                </button>
            </div>

            <!-- Formulario para registrar un cliente (oculto por defecto) -->
            <div id="formularioCliente" class="hidden bg-white p-6 rounded-lg shadow-lg mb-8">
                <h2 class="text-xl font-semibold mb-4">Registrar Cliente</h2>
                <form method="POST">
                    <div class="mb-4">
                        <label for="nombre_completo" class="block text-sm font-medium text-gray-700">Nombre Completo</label>
                        <input type="text" name="nombre_completo" id="nombre_completo" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md" required>
                    </div>
                    <div class="mb-4">
                        <label for="documento_identidad" class="block text-sm font-medium text-gray-700">Documento de Identidad</label>
                        <input type="text" name="documento_identidad" id="documento_identidad" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md" required>
                    </div>
                    <div class="mb-4">
                        <label for="correo" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                        <input type="email" name="correo" id="correo" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md" required>
                    </div>
                    <div class="mb-4">
                        <label for="telefono" class="block text-sm font-medium text-gray-700">Teléfono</label>
                        <input type="text" name="telefono" id="telefono" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md" required>
                    </div>
                    <button type="submit" name="registrar_cliente" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">
                        Registrar Cliente
                    </button>
                    <button type="button" onclick="ocultarFormulario('formularioCliente')" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                        Cancelar
                    </button>
                </form>
            </div>

            <!-- Formulario para asignar un producto financiero (oculto por defecto) -->
            <div id="formularioProducto" class="hidden bg-white p-6 rounded-lg shadow-lg mb-8">
                <h2 class="text-xl font-semibold mb-4">Asignar Producto Financiero</h2>
                <form method="POST">
                    <div class="mb-4">
                        <label for="id_cliente" class="block text-sm font-medium text-gray-700">Cliente</label>
                        <select name="id_cliente" id="id_cliente" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md" required>
                            <?php foreach ($clientes as $cliente): ?>
                                <option value="<?php echo $cliente['id_cliente']; ?>"><?php echo htmlspecialchars($cliente['nombre_completo']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="tipo_producto" class="block text-sm font-medium text-gray-700">Tipo de Producto</label>
                        <select name="tipo_producto" id="tipo_producto" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md" required>
                            <option value="Cuenta">Cuenta</option>
                            <option value="Tarjeta">Tarjeta</option>
                            <option value="Prestamo">Préstamo</option>
                            <option value="Seguro">Seguro</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="detalle_producto" class="block text-sm font-medium text-gray-700">Detalles del Producto</label>
                        <textarea name="detalle_producto" id="detalle_producto" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md" required></textarea>
                    </div>
                    <button type="submit" name="asignar_producto" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">
                        Asignar Producto
                    </button>
                    <button type="button" onclick="ocultarFormulario('formularioProducto')" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                        Cancelar
                    </button>
                </form>
            </div>

            <!-- Formulario para registrar una transacción (oculto por defecto) -->
            <div id="formularioTransaccion" class="hidden bg-white p-6 rounded-lg shadow-lg mb-8">
                <h2 class="text-xl font-semibold mb-4">Registrar Transacción</h2>
                <form method="POST">
                    <div class="mb-4">
                        <label for="id_producto" class="block text-sm font-medium text-gray-700">Producto Financiero</label>
                        <select name="id_producto" id="id_producto" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md" required>
                            <?php foreach ($productos as $producto): ?>
                                <option value="<?php echo $producto['id_producto']; ?>">
                                    <?php echo htmlspecialchars($producto['tipo_producto']); ?> - <?php echo htmlspecialchars($producto['nombre_completo']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="monto" class="block text-sm font-medium text-gray-700">Monto</label>
                        <input type="number" step="0.01" name="monto" id="monto" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md" required>
                    </div>
                    <button type="submit" name="registrar_transaccion" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">
                        Registrar Transacción
                    </button>
                    <button type="button" onclick="ocultarFormulario('formularioTransaccion')" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                        Cancelar
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Función para mostrar un formulario
        function mostrarFormulario(idFormulario) {
            // Ocultar todos los formularios
            document.getElementById('formularioCliente').style.display = 'none';
            document.getElementById('formularioProducto').style.display = 'none';
            document.getElementById('formularioTransaccion').style.display = 'none';

            // Mostrar el formulario solicitado
            document.getElementById(idFormulario).style.display = 'block';
        }

        // Función para ocultar un formulario
        function ocultarFormulario(idFormulario) {
            document.getElementById(idFormulario).style.display = 'none';
        }
    </script>
</body>
</html>