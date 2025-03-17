<?php
$host = 'mysql'; // Nombre del servicio en docker-compose
$dbname = 'acoeemprendedores';
$username = 'admin';
$password = 'adminpassword';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Conexión exitosa a la base de datos.";
} catch (PDOException $e) {
    die("Error en la conexión: " . $e->getMessage());
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <title>Login - ACOEMPRENDEDORES</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center h-screen bg-gray-100">
    <form method="POST" class="bg-white p-8 rounded-lg shadow-lg w-96">
        <h2 class="text-2xl font-bold mb-6">Iniciar Sesión</h2>
        <?php if (isset($error)): ?>
            <p class="text-red-500 mb-4"><?php echo $error; ?></p>
        <?php endif; ?>
        <label class="block mb-4">
            <span class="text-gray-700">Usuario</span>
            <input type="text" name="usuario" class="w-full p-2 border rounded" required>
        </label>
        <label class="block mb-6">
            <span class="text-gray-700">Contraseña</span>
            <input type="password" name="clave" class="w-full p-2 border rounded" required>
        </label>
        <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600">Acceder</button>
    </form>
</body>
</html>