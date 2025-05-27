<?php
session_start();
require '../../database/conexion.php';

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'empleado') {
    http_response_code(403);
    echo json_encode(['error' => 'No autorizado']);
    exit();
}

$id_empleado = $_SESSION['usuario_id']; // Asegúrate que este sea el id_empleado

$stmt = $pdo->prepare("SELECT id_notificacion, mensaje, fecha, leida FROM notificaciones WHERE id_empleado = ? ORDER BY fecha DESC LIMIT 10");
$stmt->execute([$id_empleado]);
$notificaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($notificaciones);