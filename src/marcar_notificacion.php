<?php
session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'empleado') {
    header('Location: login.php');
    exit();
}

require 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_notificacion'])) {
    $id_notificacion = $_POST['id_notificacion'];

    $stmt = $pdo->prepare("UPDATE notificaciones SET leida = TRUE WHERE id_notificacion = ?");
    $stmt->execute([$id_notificacion]);

    header('Location: vista-empleado.php');
    exit();
}
?>