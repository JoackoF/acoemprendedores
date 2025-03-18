<?php
session_start();

// Verificar que el usuario es un empleado
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'empleado') {
    header('Location: login.php');
    exit();
}

// Datos quemados para pruebas (simulando datos de la base de datos)
$transacciones = [
    ['id_transaccion' => 1, 'monto' => 150.75, 'fecha_transaccion' => '2023-10-01', 'cliente' => 'Juan Pérez'],
    ['id_transaccion' => 2, 'monto' => 200.50, 'fecha_transaccion' => '2023-10-02', 'cliente' => 'María López'],
    ['id_transaccion' => 3, 'monto' => 300.00, 'fecha_transaccion' => '2023-10-03', 'cliente' => 'Carlos Gómez'],
];
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
        <?php include 'sidebar-empleado.php'; ?>

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