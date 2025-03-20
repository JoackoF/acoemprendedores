<?php
session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'empleado') {
    header('Location: login.php');
    exit();
}

require 'conexion.php';

if (isset($_GET['id'])) {
    $id_solicitud = $_GET['id'];

    $stmt = $pdo->prepare("UPDATE solicitudes_productos SET estado = 'Aprobada', fecha_respuesta = NOW(), id_empleado_respuesta = ? WHERE id_solicitud = ?");
    $stmt->execute([$_SESSION['usuario_id'], $id_solicitud]);

    $mensaje = "Su solicitud de producto ha sido aprobada.";
    $stmt = $pdo->prepare("INSERT INTO notificaciones (id_empleado, mensaje) VALUES (?, ?)");
    $stmt->execute([$_SESSION['usuario_id'], $mensaje]);

    header('Location: dashboard_empleado.php');
    exit();
}
?>