<?php
session_start();
require '../database/conexion.php';

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'empleado') {
    header('Location: ../auth/login.php');
    exit();
}

$id_empleado = $_SESSION['usuario_id'];

// Procesar respuesta
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['responder'])) {
    $id_solicitud = $_POST['id_solicitud'];
    $accion = $_POST['accion'];
    if ($accion === 'Aprobada') {
        // Aquí puedes mostrar el formulario según el tipo de producto (ver abajo)
        $_SESSION['aprobar_solicitud'] = $id_solicitud;
        header('Location: empleado-solicitud-formulario.php');
        exit();
    } else {
        // Rechazar
        $pdo->prepare("UPDATE solicitudes_productos SET estado='Rechazada', id_empleado_respuesta=? WHERE id_solicitud=?")
            ->execute([$id_empleado, $id_solicitud]);
    }
}

// Obtener solicitudes pendientes
$stmt = $pdo->query("SELECT s.*, c.nombre_completo FROM solicitudes_productos s JOIN clientes c ON s.id_cliente = c.id_cliente WHERE s.estado='Pendiente'");
$solicitudes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<h2>Solicitudes de Productos</h2>
<table>
    <tr><th>Cliente</th><th>Producto</th><th>Detalle</th><th>Acción</th></tr>
    <?php foreach($solicitudes as $s): ?>
        <tr>
            <td><?= htmlspecialchars($s['nombre_completo']) ?></td>
            <td><?= $s['tipo_producto'] ?></td>
            <td><?= htmlspecialchars($s['detalle_solicitud']) ?></td>
            <td>
                <form method="POST" style="display:inline">
                    <input type="hidden" name="id_solicitud" value="<?= $s['id_solicitud'] ?>">
                    <button name="accion" value="Aprobada">Aceptar</button>
                    <button name="accion" value="Rechazada">Rechazar</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>