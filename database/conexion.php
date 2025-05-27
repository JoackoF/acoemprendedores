<?php
$host = 'aws-0-us-east-2.pooler.supabase.com';
$dbname = 'postgres';
$username = 'postgres.ayvfkaczotdbycxcneoj';
$password = 'XVPRMQ93fxGLecMe';
$port = 6543;

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error en la conexión: " . $e->getMessage());
}
?>