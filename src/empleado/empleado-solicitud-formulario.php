<?php
session_start();
require '../database/conexion.php';

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'empleado' || !isset($_SESSION['aprobar_solicitud'])) {
    header('Location: empleado-solicitudes.php');
    exit();
}

$id_solicitud = $_SESSION['aprobar_solicitud'];
$stmt = $pdo->prepare("SELECT * FROM solicitudes_productos WHERE id_solicitud=?");
$stmt->execute([$id_solicitud]);
$solicitud = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$solicitud) {
    echo "Solicitud no encontrada.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aprobar'])) {
    // Aquí debes insertar el producto correspondiente en la tabla adecuada
    // Ejemplo para cuenta:
    if ($solicitud['tipo_producto'] === 'Cuenta') {
        // ...insertar en productos_financieros y cuentas...
    }
    // Marcar solicitud como aprobada
    $pdo->prepare("UPDATE solicitudes_productos SET estado='Aprobada', id_empleado_respuesta=? WHERE id_solicitud=?")
        ->execute([$_SESSION['usuario_id'], $id_solicitud]);
    unset($_SESSION['aprobar_solicitud']);
    header('Location: empleado-solicitudes.php');
    exit();
}
?>
<h2>Completar datos para <?= $solicitud['tipo_producto'] ?></h2>
<form method="POST">
    <?php if ($solicitud['tipo_producto'] === 'Cuenta'): ?>
        <label>Número de cuenta:</label>
        <input type="text" name="numero_cuenta" required>
        <label>Monto de apertura:</label>
        <input type="number" name="monto_apertura" min="0" step="0.01" required>
    <?php elseif ($solicitud['tipo_producto'] === 'Tarjeta'): ?>
        <!-- Campos para tarjeta -->
    <?php elseif ($solicitud['tipo_producto'] === 'Prestamo'): ?>
        <!-- Campos para préstamo -->
    <?php elseif ($solicitud['tipo_producto'] === 'Seguro'): ?>
        <!-- Campos para seguro -->
    <?php endif; ?>
    <button type="submit" name="aprobar">Aprobar y crear producto</button>
</form>