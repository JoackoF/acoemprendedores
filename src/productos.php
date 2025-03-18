<?php
session_start();
require 'conexion.php';

// Verificar que el usuario es un administrador
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Obtener lista de productos financieros
$productos = $pdo->query("SELECT pf.id_producto, pf.tipo_producto, pf.detalle_producto, c.nombre_completo 
                          FROM productos_financieros pf
                          JOIN clientes c ON pf.id_cliente = c.id_cliente")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos Financieros - ACOEMPRENDEDORES</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex">
        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>

        <!-- Main content -->
        <div class="flex-1 p-6">
            <h1 class="text-3xl font-semibold mb-6">Productos Financieros</h1>

            <div class="mb-6">
                <a href="dashboard.php" class="bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-700">Regresar</a>
            </div>

            <table class="min-w-full table-auto bg-white rounded-lg shadow-lg">
                <thead>
                    <tr>
                        <th class="px-6 py-4 text-left">Tipo Producto</th>
                        <th class="px-6 py-4 text-left">Cliente</th>
                        <th class="px-6 py-4 text-left">Detalles</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productos as $producto): ?>
                        <tr>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($producto['tipo_producto']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($producto['nombre_completo']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($producto['detalle_producto']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
