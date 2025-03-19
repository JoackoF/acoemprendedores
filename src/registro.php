<?php
require 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $codigo_empleado = $_POST['codigo_empleado'] ?? '';
    $nombre_completo = $_POST['nombre_completo'] ?? '';
    $estado_familiar = $_POST['estado_familiar'] ?? '';
    $documento_identidad = $_POST['documento_identidad'] ?? '';
    $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? '';
    $edad = $_POST['edad'] ?? '';
    $direccion = $_POST['direccion'] ?? '';
    $puesto = $_POST['puesto'] ?? '';
    $departamento = $_POST['departamento'] ?? '';
    $sueldo = $_POST['sueldo'] ?? '';
    $profesion = $_POST['profesion'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $usuario = $_POST['usuario'] ?? '';
    $clave = $_POST['clave'] ?? '';
    $rol = $_POST['rol'] ?? 'cajero';

    if (!empty($codigo_empleado) && !empty($nombre_completo) && !empty($usuario) && !empty($clave) && !empty($rol)) {
        $hashedClave = password_hash($clave, PASSWORD_DEFAULT);

        $stmtEmpleado = $pdo->prepare("INSERT INTO empleados (codigo_empleado, nombre_completo, estado_familiar, documento_identidad, fecha_nacimiento, edad, direccion, puesto, departamento, sueldo, profesion, correo, telefono) 
                                      VALUES (:codigo_empleado, :nombre_completo, :estado_familiar, :documento_identidad, :fecha_nacimiento, :edad, :direccion, :puesto, :departamento, :sueldo, :profesion, :correo, :telefono)");
        $stmtEmpleado->execute([
            'codigo_empleado' => $codigo_empleado,
            'nombre_completo' => $nombre_completo,
            'estado_familiar' => $estado_familiar,
            'documento_identidad' => $documento_identidad,
            'fecha_nacimiento' => $fecha_nacimiento,
            'edad' => $edad,
            'direccion' => $direccion,
            'puesto' => $puesto,
            'departamento' => $departamento,
            'sueldo' => $sueldo,
            'profesion' => $profesion,
            'correo' => $correo,
            'telefono' => $telefono
        ]);

        $idEmpleado = $pdo->lastInsertId();

        $stmtUsuario = $pdo->prepare("INSERT INTO usuarios (id_empleado, usuario, clave, rol) VALUES (:id_empleado, :usuario, :clave, :rol)");
        $stmtUsuario->execute([
            'id_empleado' => $idEmpleado,
            'usuario' => $usuario,
            'clave' => $hashedClave,
            'rol' => $rol
        ]);

        header('Location: dashboard.php');
        exit();
    } else {
        $error = 'Por favor, completa todos los campos.';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario - ACOEMPRENDEDORES</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <form method="POST" class="bg-white p-8 rounded-lg shadow-lg w-96">
        <h2 class="text-2xl font-bold mb-6 text-center">Registrar Usuario</h2>

        <?php if (isset($error)): ?>
            <p class="text-red-500 mb-4 text-center"><?php echo $error; ?></p>
        <?php endif; ?>

        <div class="mb-4">
            <label class="block text-gray-700">Código de Empleado</label>
            <input type="text" name="codigo_empleado" class="w-full p-2 border rounded" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Nombre Completo</label>
            <input type="text" name="nombre_completo" class="w-full p-2 border rounded" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Estado Familiar</label>
            <select name="estado_familiar" class="w-full p-2 border rounded" required>
                <option value="Soltero">Soltero</option>
                <option value="Casado">Casado</option>
                <option value="Divorciado">Divorciado</option>
                <option value="Viudo">Viudo</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Documento de Identidad</label>
            <input type="text" name="documento_identidad" class="w-full p-2 border rounded" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Fecha de Nacimiento</label>
            <input type="date" name="fecha_nacimiento" class="w-full p-2 border rounded" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Edad</label>
            <input type="number" name="edad" class="w-full p-2 border rounded" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Dirección</label>
            <textarea name="direccion" class="w-full p-2 border rounded" required></textarea>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Puesto</label>
            <input type="text" name="puesto" class="w-full p-2 border rounded" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Departamento</label>
            <select name="departamento" class="w-full p-2 border rounded" required>
                <option value="Finanzas">Finanzas</option>
                <option value="Atención al cliente">Atención al cliente</option>
                <option value="Gerencia">Gerencia</option>
                <option value="Servicios varios">Servicios varios</option>
                <option value="Seguridad">Seguridad</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Sueldo</label>
            <input type="number" name="sueldo" class="w-full p-2 border rounded" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Profesión</label>
            <input type="text" name="profesion" class="w-full p-2 border rounded">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Correo</label>
            <input type="email" name="correo" class="w-full p-2 border rounded" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Teléfono</label>
            <input type="text" name="telefono" class="w-full p-2 border rounded" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Nombre de Usuario</label>
            <input type="text" name="usuario" class="w-full p-2 border rounded" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Contraseña</label>
            <input type="password" name="clave" class="w-full p-2 border rounded" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Rol</label>
            <select name="rol" class="w-full p-2 border rounded" required>
                <option value="admin">Administrador</option>
                <option value="cajero">Cajero</option>
                <option value="gerente">Gerente</option>
            </select>
        </div>

        <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600">Registrar</button>
    </form>
</body>
</html>
