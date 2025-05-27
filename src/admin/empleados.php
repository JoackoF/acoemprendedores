<?php
session_start();
require '../../database/conexion.php';

// Verificar que el usuario es un administrador
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: ../../auth/login.php');
    exit();
}

// Obtener lista de empleados
$empleados = $pdo->query("SELECT * FROM empleados")->fetchAll(PDO::FETCH_ASSOC);

// Inicializar variable para mostrar credenciales
$credencialesMostrar = null;

// Procesar formulario para agregar empleado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar_empleado'])) {
    $nombre = $_POST['nombre'];
    $puesto = $_POST['puesto'];
    $departamento = $_POST['departamento'];

    // Insertar empleado
    $stmt = $pdo->prepare("INSERT INTO empleados (nombre_completo, puesto, departamento) VALUES (?, ?, ?)");
    $stmt->execute([$nombre, $puesto, $departamento]);
    $idEmpleado = $pdo->lastInsertId();

    // Generar usuario y contraseña aleatorios
    $username = strtolower(explode(' ', trim($nombre))[0]) . rand(100, 999);
    $password = bin2hex(random_bytes(4)); // 8 caracteres hex

    // Guardar usuario (ajusta la tabla y campos según tu estructura)
    $stmt = $pdo->prepare("INSERT INTO usuarios (id_empleado, usuario, contrasena) VALUES (?, ?, ?)");
    $stmt->execute([$idEmpleado, $username, password_hash($password, PASSWORD_DEFAULT)]);

    // Guardar credenciales para mostrar
    $_SESSION['credencialesMostrar'] = [
        'username' => $username,
        'password' => $password
    ];

    header('Location: empleados.php');
    exit();
}

// Mostrar credenciales si existen en sesión
if (isset($_SESSION['credencialesMostrar'])) {
    $credencialesMostrar = $_SESSION['credencialesMostrar'];
    unset($_SESSION['credencialesMostrar']);
}

// Procesar formulario para editar empleado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_empleado'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $puesto = $_POST['puesto'];
    $departamento = $_POST['departamento'];

    $stmt = $pdo->prepare("UPDATE empleados SET nombre_completo = ?, puesto = ?, departamento = ? WHERE id_empleado = ?");
    $stmt->execute([$nombre, $puesto, $departamento, $id]);

    header('Location: empleados.php');
    exit();
}

// Procesar solicitud para eliminar empleado
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];

    $stmt = $pdo->prepare("DELETE FROM empleados WHERE id_empleado = ?");
    $stmt->execute([$id]);

    header('Location: empleados.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empleados - ACOEMPRENDEDORES</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex">
        <!-- Sidebar -->
        <?php include '../partials/sidebar.php'; ?>

        <!-- Main content -->
        <div class="flex-1 p-6">
            <h1 class="text-3xl font-semibold mb-6">Empleados</h1>

            <div class="mb-6">
                <a href="dashboard.php" class="bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-700">Regresar</a>
            </div>

            <!-- Botón para agregar empleado -->
            <button onclick="mostrarFormularioAgregar()" class="bg-blue-500 text-white px-4 py-2 rounded-md mb-6 hover:bg-blue-600">
                Agregar Empleado
            </button>

            <!-- Formulario para agregar empleado (oculto por defecto) -->
            <div id="formularioAgregar" class="hidden mb-6">
                <form method="POST" class="bg-white p-6 rounded-lg shadow-lg">
                    <h2 class="text-xl font-semibold mb-4">Agregar Empleado</h2>
                    <div class="mb-4">
                        <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                        <input type="text" name="nombre" id="nombre" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md" required>
                    </div>
                    <div class="mb-4">
                        <label for="puesto" class="block text-sm font-medium text-gray-700">Puesto</label>
                        <input type="text" name="puesto" id="puesto" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md" required>
                    </div>
                    <div class="mb-4">
                        <label for="departamento" class="block text-sm font-medium text-gray-700">Departamento</label>
                        <input type="text" name="departamento" id="departamento" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md" required>
                    </div>
                    <button type="submit" name="agregar_empleado" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">
                        Guardar
                    </button>
                    <button type="button" onclick="ocultarFormularioAgregar()" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                        Cancelar
                    </button>
                </form>
            </div>

            <!-- Tabla de empleados -->
            <table class="min-w-full table-auto bg-white rounded-lg shadow-lg">
                <thead>
                    <tr>
                        <th class="px-6 py-4 text-left">Nombre</th>
                        <th class="px-6 py-4 text-left">Puesto</th>
                        <th class="px-6 py-4 text-left">Departamento</th>
                        <th class="px-6 py-4 text-left">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($empleados as $empleado): ?>
                        <tr>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($empleado['nombre_completo']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($empleado['puesto']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($empleado['departamento']); ?></td>
                            <td class="px-6 py-4">
                                <!-- Botón para editar empleado -->
                                <button onclick="mostrarFormularioEditar(<?php echo $empleado['id_empleado']; ?>)" class="bg-yellow-500 text-white px-4 py-2 rounded-md hover:bg-yellow-600">
                                    Editar
                                </button>
                                <!-- Botón para eliminar empleado -->
                                <a href="empleados.php?eliminar=<?php echo $empleado['id_empleado']; ?>" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600" onclick="return confirm('¿Estás seguro de eliminar este empleado?');">
                                    Eliminar
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Formulario para editar empleado (oculto por defecto) -->
            <div id="formularioEditar" class="hidden mb-6">
                <form method="POST" class="bg-white p-6 rounded-lg shadow-lg">
                    <h2 class="text-xl font-semibold mb-4">Editar Empleado</h2>
                    <input type="hidden" name="id" id="editarId">
                    <div class="mb-4">
                        <label for="editarNombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                        <input type="text" name="nombre" id="editarNombre" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md" required>
                    </div>
                    <div class="mb-4">
                        <label for="editarPuesto" class="block text-sm font-medium text-gray-700">Puesto</label>
                        <input type="text" name="puesto" id="editarPuesto" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md" required>
                    </div>
                    <div class="mb-4">
                        <label for="editarDepartamento" class="block text-sm font-medium text-gray-700">Departamento</label>
                        <input type="text" name="departamento" id="editarDepartamento" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md" required>
                    </div>
                    <button type="submit" name="editar_empleado" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">
                        Guardar
                    </button>
                    <button type="button" onclick="ocultarFormularioEditar()" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                        Cancelar
                    </button>
                </form>
            </div>

            <!-- Mostrar credenciales generadas -->
            <?php if ($credencialesMostrar): ?>
                <div class="bg-white p-4 rounded-lg shadow-lg mb-6">
                    <h2 class="text-xl font-semibold mb-4">Credenciales Generadas</h2>
                    <p class="text-sm text-gray-700 mb-2">Usuario: <strong><?php echo htmlspecialchars($credencialesMostrar['username']); ?></strong></p>
                    <p class="text-sm text-gray-700">Contraseña: <strong><?php echo htmlspecialchars($credencialesMostrar['password']); ?></strong></p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Mostrar y ocultar formularios
        function mostrarFormularioAgregar() {
            document.getElementById('formularioAgregar').style.display = 'block';
        }

        function ocultarFormularioAgregar() {
            document.getElementById('formularioAgregar').style.display = 'none';
        }

        function mostrarFormularioEditar(id) {
            // Obtener datos del empleado
            const empleado = <?php echo json_encode($empleados); ?>.find(e => e.id_empleado == id);
            document.getElementById('editarId').value = empleado.id_empleado;
            document.getElementById('editarNombre').value = empleado.nombre_completo;
            document.getElementById('editarPuesto').value = empleado.puesto;
            document.getElementById('editarDepartamento').value = empleado.departamento;
            document.getElementById('formularioEditar').style.display = 'block';
        }

        function ocultarFormularioEditar() {
            document.getElementById('formularioEditar').style.display = 'none';
        }
    </script>
</body>
</html>