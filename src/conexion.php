<?php
// conexion.php - Maneja la conexión a la base de datos
$host = 'mysql'; // Nombre del servicio en docker-compose
$dbname = 'acoeemprendedores';
$username = 'admin';
$password = 'adminpassword';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error en la conexión: " . $e->getMessage());
}
?>
