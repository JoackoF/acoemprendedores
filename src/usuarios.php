<?php
session_start();
require 'conexion.php';

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: login.php');
    exit();
}

$usuarios = $pdo->query("SELECT u.id_usuario, u.usuario, u.rol, e.nombre_completo 
                         FROM usuarios u
                         JOIN empleados e ON u.id_empleado = e.id_empleado")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios - ACOEMPRENDEDORES</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex">
        <?php include 'sidebar.php'; ?>

        <div class="flex-1 p-6">
            <h1 class="text-3xl font-semibold mb-6">Usuarios</h1>

            <div class="mb-6">
                <a href="dashboard.php" class="bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-700">Regresar</a>
            </div>

            <table class="min-w-full table-auto bg-white rounded-lg shadow-lg">
                <thead>
                    <tr>
                        <th class="px-6 py-4 text-left">Usuario</th>
                        <th class="px-6 py-4 text-left">Empleado</th>
                        <th class="px-6 py-4 text-left">Rol</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($usuario['usuario']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($usuario['nombre_completo']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($usuario['rol']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
