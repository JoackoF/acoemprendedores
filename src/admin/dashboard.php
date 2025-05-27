<?php
session_start();
require '../database/conexion.php';

// Verificar que el usuario es un administrador
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit();
}

// Consultas para obtener datos clave del sistema
$empleadosCount = $pdo->query("SELECT COUNT(*) FROM empleados")->fetchColumn();
$clientesCount = $pdo->query("SELECT COUNT(*) FROM clientes")->fetchColumn();
$productosCount = $pdo->query("SELECT COUNT(*) FROM productos_financieros")->fetchColumn();
$transacciones = $pdo->query("SELECT * FROM transacciones ORDER BY fecha_transaccion DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);

// Obtener listas detalladas
$empleados = $pdo->query("SELECT nombre_completo, puesto FROM empleados LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
$clientes = $pdo->query("SELECT nombre_completo, documento_identidad FROM clientes LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);

// Productos financieros con detalles
$productos = $pdo->query("SELECT pf.id_producto, pf.tipo_producto, pf.detalle_producto,
    COALESCE(c.numero_cuenta, t.numero_tarjeta, p.numero_referencia, 'N/A') AS identificador
    FROM productos_financieros pf
    LEFT JOIN cuentas c ON pf.id_producto = c.id_producto
    LEFT JOIN tarjetas t ON pf.id_producto = t.id_producto
    LEFT JOIN prestamos p ON pf.id_producto = p.id_producto
    LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - ACOEMPRENDEDORES</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="flex h-full">
        <!-- Sidebar -->
        <?php include '../partials/sidebar.php'; ?>

        <!-- Main content -->
        <div class="flex-1 p-6">
            <h1 class="text-3xl font-semibold mb-6">Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre']); ?>!
            </h1>

            <!-- Resumen de datos -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h2 class="text-xl font-semibold mb-4">Empleados</h2>
                    <p class="text-3xl font-bold"><?php echo $empleadosCount; ?></p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h2 class="text-xl font-semibold mb-4">Clientes</h2>
                    <p class="text-3xl font-bold"><?php echo $clientesCount; ?></p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h2 class="text-xl font-semibold mb-4">Productos Financieros</h2>
                    <p class="text-3xl font-bold"><?php echo $productosCount; ?></p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h2 class="text-xl font-semibold mb-4">Últimas Transacciones</h2>
                    <ul class="text-sm">
                        <?php foreach ($transacciones as $transaccion): ?>
                            <li class="mb-2">
                                <strong>#<?php echo $transaccion['id_transaccion']; ?></strong>
                                <br>
                                <span class="text-gray-600">Monto: <?php echo $transaccion['monto']; ?> | Fecha:
                                    <?php echo $transaccion['fecha_transaccion']; ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <!-- Listas detalladas -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Lista de empleados -->
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h2 class="text-xl font-semibold mb-4">Últimos Empleados</h2>
                    <ul>
                        <?php foreach ($empleados as $empleado): ?>
                            <li><?php echo htmlspecialchars($empleado['nombre_completo']); ?> -
                                <?php echo htmlspecialchars($empleado['puesto']); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- Lista de clientes -->
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h2 class="text-xl font-semibold mb-4">Últimos Clientes</h2>
                    <ul>
                        <?php foreach ($clientes as $cliente): ?>
                            <li><?php echo htmlspecialchars($cliente['nombre_completo']); ?> -
                                <?php echo htmlspecialchars($cliente['documento_identidad']); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- Lista de productos financieros -->
                <div class="bg-white p-6 rounded-lg shadow-lg col-span-2">
                    <h2 class="text-xl font-semibold mb-4">Últimos Productos Financieros</h2>
                    <ul>
                        <?php foreach ($productos as $producto): ?>
                            <li><?php echo htmlspecialchars($producto['tipo_producto']); ?> -
                                <?php echo htmlspecialchars($producto['identificador']); ?>
                                (<?php echo htmlspecialchars($producto['detalle_producto']); ?>)</li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>

</html>