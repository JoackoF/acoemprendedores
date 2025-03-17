<?php
require 'db.php';
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'Admin') {
    header('Location: login.php');
    exit();
}

// Obtener estadísticas
$totalEmpleados = $pdo->query("SELECT COUNT(*) FROM empleados")->fetchColumn();
$totalClientes = $pdo->query("SELECT COUNT(*) FROM clientes")->fetchColumn();
$totalCuentas = $pdo->query("SELECT COUNT(*) FROM cuentas")->fetchColumn();
$totalPrestamos = $pdo->query("SELECT COUNT(*) FROM prestamos")->fetchColumn();
$totalTransacciones = $pdo->query("SELECT COUNT(*) FROM transacciones")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Dashboard - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <h1 class="text-3xl font-bold mb-8">Dashboard del Administrador</h1>

    <div class="grid grid-cols-2 gap-8">
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-2">Total de Empleados</h2>
            <p class="text-3xl"><?php echo $totalEmpleados; ?></p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-2">Total de Clientes</h2>
            <p class="text-3xl"><?php echo $totalClientes; ?></p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-2">Total de Cuentas</h2>
            <p class="text-3xl"><?php echo $totalCuentas; ?></p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-2">Total de Préstamos</h2>
            <p class="text-3xl"><?php echo $totalPrestamos; ?></p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-2">Total de Transacciones</h2>
            <p class="text-3xl"><?php echo $totalTransacciones; ?></p>
        </div>
    </div>

    <a href="logout.php" class="mt-8 inline-block bg-red-500 text-white px-4 py-2 rounded">Cerrar Sesión</a>
</body>
</html>
