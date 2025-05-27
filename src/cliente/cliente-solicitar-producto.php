<?php
session_start();
require '../database/conexion.php';

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'cliente') {
    header('Location: ../auth/login.php');
    exit();
}

$id_cliente = $_SESSION['usuario_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['solicitar'])) {
    $tipo = $_POST['tipo_producto'];
    $detalle = $_POST['detalle_solicitud'];
    // Insertar solicitud
    $stmt = $pdo->prepare("INSERT INTO solicitudes_productos (id_cliente, tipo_producto, detalle_solicitud) VALUES (?, ?, ?)");
    $stmt->execute([$id_cliente, $tipo, $detalle]);
    // Notificar a todos los empleados (puedes filtrar por rol o departamento)
    $empleados = $pdo->query("SELECT id_empleado FROM empleados")->fetchAll(PDO::FETCH_COLUMN);
    foreach ($empleados as $id_empleado) {
        $msg = "Nueva solicitud de $tipo de cliente #" . $id_cliente;
        $pdo->prepare("INSERT INTO notificaciones (id_empleado, mensaje) VALUES (?, ?)")->execute([$id_empleado, $msg]);
    }
    $msg = "Solicitud enviada correctamente.";
}
?>
<h2>Solicitar Producto</h2>
<?php if (isset($msg)) echo "<div>$msg</div>"; ?>
<form method="POST">
    <label>Tipo de producto:</label>
    <select name="tipo_producto" required>
        <option value="Cuenta">Cuenta</option>
        <option value="Tarjeta">Tarjeta</option>
        <option value="Prestamo">Préstamo</option>
        <option value="Seguro">Seguro</option>
    </select>
    <br>
    <label>Detalle:</label>
    <textarea name="detalle_solicitud" required></textarea>
    <br>
    <button type="submit" name="solicitar">Solicitar</button>
</form>