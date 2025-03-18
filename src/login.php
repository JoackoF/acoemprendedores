<?php
session_start();

// Datos quemados para pruebas
$usuarioFijo = 'admin';
$claveFija = 'admin123';

// Procesar el formulario de login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $clave = $_POST['clave'] ?? '';

    if ($usuario === $usuarioFijo && $clave === $claveFija) {
        $_SESSION['usuario_id'] = 1; // ID de ejemplo
        $_SESSION['nombre'] = 'Administrador';
        $_SESSION['rol'] = 'admin';
        header('Location: dashboard.php');
        exit();
    } else {
        $error = 'Usuario o contraseña incorrectos';
    }
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
