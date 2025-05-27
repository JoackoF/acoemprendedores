<?php
session_start();
require '../database/conexion.php';

// Verifica que el usuario es cliente
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'cliente') {
    header('Location: ../auth/login.php');
    exit();
}

$id_cliente = $_SESSION['usuario_id'];

// Obtener saldo actual (puedes tener una tabla 'carteras' o sumar los movimientos)
$stmt = $pdo->prepare("SELECT IFNULL(SUM(monto),0) as saldo FROM movimientos_cartera WHERE id_cliente = ?");
$stmt->execute([$id_cliente]);
$saldo = $stmt->fetchColumn();

// Procesar recarga
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recargar'])) {
    $monto = floatval($_POST['monto']);
    if ($monto > 0) {
        $stmt = $pdo->prepare("INSERT INTO movimientos_cartera (id_cliente, monto, tipo, descripcion, fecha) VALUES (?, ?, 'recarga', 'Recarga de saldo', NOW())");
        $stmt->execute([$id_cliente, $monto]);
        header('Location: cliente-cartera.php');
        exit();
    }
}

// Obtener movimientos
$movs = $pdo->prepare("SELECT * FROM movimientos_cartera WHERE id_cliente = ? ORDER BY fecha DESC");
$movs->execute([$id_cliente]);
$movimientos = $movs->fetchAll(PDO::FETCH_ASSOC);
?>
<h2>Mi Cartera Virtual</h2>
<p>Saldo actual: <strong>L. <?= number_format($saldo,2) ?></strong></p>
<form method="POST" class="mb-4">
    <input type="number" name="monto" min="1" step="0.01" required placeholder="Monto a recargar">
    <button type="submit" name="recargar">Recargar</button>
</form>
<h3>Movimientos</h3>
<table>
    <tr><th>Fecha</th><th>Tipo</th><th>Monto</th><th>Descripción</th></tr>
    <?php foreach($movimientos as $m): ?>
        <tr>
            <td><?= $m['fecha'] ?></td>
            <td><?= ucfirst($m['tipo']) ?></td>
            <td>L. <?= number_format($m['monto'],2) ?></td>
            <td><?= htmlspecialchars($m['descripcion']) ?></td>
        </tr>
    <?php endforeach; ?>
</table>