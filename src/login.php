<?php
session_start();

require 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $clave = $_POST['clave'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = ?");
    $stmt->execute([$usuario]);
    $usuario_db = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario_db && password_verify($clave, $usuario_db['clave'])) {
        $_SESSION['usuario_id'] = $usuario_db['id_usuario'];
        $_SESSION['nombre'] = $usuario_db['usuario'];
        $_SESSION['rol'] = $usuario_db['rol'];

        // Redirigir según el rol
        if ($_SESSION['rol'] === 'admin') {
            header('Location: dashboard.php');
        } elseif ($_SESSION['rol'] === 'empleado') {
            header('Location: vista-empleado.php');
        }
        exit();
    } else {
        $stmt = $pdo->prepare("SELECT * FROM clientes WHERE usuario = ?");
        $stmt->execute([$usuario]);
        $cliente_db = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($cliente_db && password_verify($clave, $cliente_db['contrasena'])) {
            // Es un cliente
            $_SESSION['usuario_id'] = $cliente_db['id_cliente'];
            $_SESSION['nombre'] = $cliente_db['nombre_completo'];
            $_SESSION['rol'] = 'cliente';

            header('Location: vista-cliente.php');
            exit();
        } else {
            $error = 'Usuario o contraseña incorrectos';
        }
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

        <button type="submit" class="w-full bg-gray-700 text-white p-2 rounded hover:bg-gray-600">Acceder</button>
    </form>
</body>
</html>