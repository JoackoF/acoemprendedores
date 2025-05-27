<?php
session_start();

// Verificar que el usuario es un empleado
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'empleado') {
    header('Location: ../../auth/login.php');
    exit();
}

require '../../database/conexion.php';

// Obtener lista de clientes desde la base de datos
$clientes = $pdo->query("SELECT id_cliente, nombre_completo, correo, telefono FROM clientes")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes - ACOEMPRENDEDORES</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex">
        <!-- Sidebar -->
        <?php include '../partials/sidebar-empleado.php'; ?>

        <!-- Main content -->
        <div class="flex-1 p-6">
            <h1 class="text-3xl font-semibold mb-6">Clientes</h1>

            <div class="mb-6">
                <a href="vista-empleado.php" class="bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-700">Regresar</a>
            </div>

            <!-- Tabla de clientes -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-xl font-semibold mb-4">Lista de Clientes</h2>
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th class="px-6 py-4 text-left">ID Cliente</th>
                            <th class="px-6 py-4 text-left">Nombre</th>
                            <th class="px-6 py-4 text-left">Correo</th>
                            <th class="px-6 py-4 text-left">Teléfono</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clientes as $cliente): ?>
                            <tr>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($cliente['id_cliente']); ?></td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($cliente['nombre_completo']); ?></td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($cliente['correo']); ?></td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($cliente['telefono']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>