<?php
session_start();

// Verificar que el usuario es un empleado
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'empleado') {
    header('Location: ../auth/login.php');
    exit();
}

require '../../database/conexion.php';

// Consulta dinámica de transacciones
$stmt = $pdo->query("
    SELECT 
        t.id_transaccion, 
        t.monto, 
        t.fecha_transaccion, 
        c.nombre_completo AS cliente
    FROM transacciones t
    JOIN productos_financieros pf ON t.id_producto = pf.id_producto
    JOIN clientes c ON pf.id_cliente = c.id_cliente
    ORDER BY t.fecha_transaccion DESC
");
$transacciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transacciones - ACOEMPRENDEDORES</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex">
        <!-- Sidebar -->
        <?php include '../partials/sidebar-empleado.php'; ?>

        <!-- Main content -->
        <div class="flex-1 p-6">
            <h1 class="text-3xl font-semibold mb-6">Transacciones</h1>

            <div class="mb-6">
                <a href="vista-empleado.php" class="bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-700">Regresar</a>
            </div>

            <!-- Tabla de transacciones -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-xl font-semibold mb-4">Lista de Transacciones</h2>
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
                        <?php foreach ($transacciones as $transaccion): ?>
                            <tr>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($transaccion['id_transaccion']); ?></td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($transaccion['cliente']); ?></td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($transaccion['monto']); ?></td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($transaccion['fecha_transaccion']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>