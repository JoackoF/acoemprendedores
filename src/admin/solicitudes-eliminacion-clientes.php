<?php
session_start();

// Verifica que el usuario es admin
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit();
}

require '../database/conexion.php';

// Procesar acción de aprobar o rechazar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && isset($_POST['id_solicitud'])) {
    $id_solicitud = $_POST['id_solicitud'];
    $accion = $_POST['accion'];

    if ($accion === 'aprobar') {
        // Obtener el id_cliente antes de eliminar
        $stmt = $pdo->prepare("SELECT id_cliente FROM solicitudes_eliminacion_clientes WHERE id_solicitud = ?");
        $stmt->execute([$id_solicitud]);
        $solicitud = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($solicitud) {
            $id_cliente = $solicitud['id_cliente'];
            // Eliminar cliente
            $pdo->prepare("DELETE FROM clientes WHERE id_cliente = ?")->execute([$id_cliente]);
        }

        // Actualizar estado de la solicitud
        $pdo->prepare("UPDATE solicitudes_eliminacion_clientes SET estado = 'Aprobada' WHERE id_solicitud = ?")->execute([$id_solicitud]);
    } elseif ($accion === 'rechazar') {
        $pdo->prepare("UPDATE solicitudes_eliminacion_clientes SET estado = 'Rechazada' WHERE id_solicitud = ?")->execute([$id_solicitud]);
    }

    header('Location: solicitudes-eliminacion-clientes.php');
    exit();
}

// Obtener todas las solicitudes pendientes
$solicitudes = $pdo->query("
    SELECT s.id_solicitud, s.motivo, s.fecha_solicitud, s.estado, 
           c.nombre_completo AS nombre_cliente, c.correo, c.telefono,
           e.nombre_completo AS nombre_empleado
    FROM solicitudes_eliminacion_clientes s
    JOIN clientes c ON s.id_cliente = c.id_cliente
    JOIN empleados e ON s.id_empleado = e.id_empleado
    ORDER BY s.fecha_solicitud DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Solicitudes de Eliminación de Clientes</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="flex">
        <?php include '../partials/sidebar.php'; ?>
        <div class="p-8">
            <h1 class="text-3xl font-semibold mb-6">Solicitudes de Eliminación de Clientes</h1>
            <a href="dashboard.php"
                class="bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-700 mb-6 inline-block">Regresar</a>
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">Cliente</th>
                            <th class="px-4 py-2">Correo</th>
                            <th class="px-4 py-2">Teléfono</th>
                            <th class="px-4 py-2">Solicitante</th>
                            <th class="px-4 py-2">Motivo</th>
                            <th class="px-4 py-2">Fecha</th>
                            <th class="px-4 py-2">Estado</th>
                            <th class="px-4 py-2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($solicitudes as $solicitud): ?>
                            <tr>
                                <td class="px-4 py-2"><?php echo htmlspecialchars($solicitud['nombre_cliente']); ?></td>
                                <td class="px-4 py-2"><?php echo htmlspecialchars($solicitud['correo']); ?></td>
                                <td class="px-4 py-2"><?php echo htmlspecialchars($solicitud['telefono']); ?></td>
                                <td class="px-4 py-2"><?php echo htmlspecialchars($solicitud['nombre_empleado']); ?></td>
                                <td class="px-4 py-2"><?php echo htmlspecialchars($solicitud['motivo']); ?></td>
                                <td class="px-4 py-2"><?php echo htmlspecialchars($solicitud['fecha_solicitud']); ?></td>
                                <td class="px-4 py-2">
                                    <?php
                                    if ($solicitud['estado'] === 'Pendiente') {
                                        echo '<span class="text-yellow-600 font-semibold">Pendiente</span>';
                                    } elseif ($solicitud['estado'] === 'Aprobada') {
                                        echo '<span class="text-green-600 font-semibold">Aprobada</span>';
                                    } else {
                                        echo '<span class="text-red-600 font-semibold">Rechazada</span>';
                                    }
                                    ?>
                                </td>
                                <td class="px-4 py-2">
                                    <?php if ($solicitud['estado'] === 'Pendiente'): ?>
                                        <form method="POST" class="inline">
                                            <input type="hidden" name="id_solicitud"
                                                value="<?php echo $solicitud['id_solicitud']; ?>">
                                            <button type="submit" name="accion" value="aprobar"
                                                class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">Aprobar</button>
                                        </form>
                                        <form method="POST" class="inline">
                                            <input type="hidden" name="id_solicitud"
                                                value="<?php echo $solicitud['id_solicitud']; ?>">
                                            <button type="submit" name="accion" value="rechazar"
                                                class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Rechazar</button>
                                        </form>
                                    <?php else: ?>
                                        <span class="text-gray-500">Sin acciones</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($solicitudes)): ?>
                            <tr>
                                <td colspan="8" class="text-center py-4 text-gray-500">No hay solicitudes.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>