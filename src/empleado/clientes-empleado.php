<?php
session_start();

// Verificar que el usuario es un empleado
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'empleado') {
    header('Location: ../auth/login.php');
    exit();
}

require '../database/conexion.php';

// Obtener lista de clientes desde la base de datos
$clientes = $pdo->query("SELECT id_cliente, nombre_completo, correo, telefono FROM clientes")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['solicitar_eliminacion'])) {
    $id_cliente = $_POST['id_cliente_eliminar'];
    $motivo = $_POST['motivo_eliminacion'];
    $id_usuario = $_SESSION['usuario_id']; // Este es el id_usuario de la tabla usuarios

    // Obtener el id_empleado correspondiente a este usuario
    $stmt = $pdo->prepare("SELECT id_empleado FROM usuarios WHERE id_usuario = ?");
    $stmt->execute([$id_usuario]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $id_empleado = $row ? $row['id_empleado'] : null;

    if ($id_empleado) {
        $stmt = $pdo->prepare("INSERT INTO solicitudes_eliminacion_clientes (id_cliente, motivo, id_empleado) VALUES (?, ?, ?)");
        $stmt->execute([$id_cliente, $motivo, $id_empleado]);
        echo "<script>alert('Solicitud enviada al administrador.'); window.location='clientes-empleado.php';</script>";
    } else {
        echo "<script>alert('Error: No se encontró el empleado asociado a este usuario.'); window.location='clientes-empleado.php';</script>";
    }
    exit();
}

// Actualizar datos del cliente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_cliente'])) {
    $id_cliente = $_POST['id_cliente_editar'];
    $nombre = $_POST['nombre_completo_editar'];
    $correo = $_POST['correo_editar'];
    $telefono = $_POST['telefono_editar'];

    $stmt = $pdo->prepare("UPDATE clientes SET nombre_completo = ?, correo = ?, telefono = ? WHERE id_cliente = ?");
    $stmt->execute([$nombre, $correo, $telefono, $id_cliente]);

    echo "<script>alert('Cliente actualizado correctamente.'); window.location='clientes-empleado.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes - ACOEMPRENDEDORES</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex">
        <!-- Sidebar -->
        <?php include '../partials/sidebar-empleado.php'; ?>

        <!-- Main content -->
        <div class="flex-1 p-6">
            <h1 class="text-3xl font-semibold mb-6">Clientes</h1>

            <div class="mb-6">
                <a href="vista-empleado.php" class="bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-700">Regresar</a>
            </div>

            <!-- Tabla de clientes -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-xl font-semibold mb-4">Lista de Clientes</h2>
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th class="px-6 py-4 text-left">ID Cliente</th>
                            <th class="px-6 py-4 text-left">Nombre</th>
                            <th class="px-6 py-4 text-left">Correo</th>
                            <th class="px-6 py-4 text-left">Teléfono</th>
                            <th class="px-6 py-4 text-left">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clientes as $cliente): ?>
                            <tr>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($cliente['id_cliente']); ?></td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($cliente['nombre_completo']); ?></td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($cliente['correo']); ?></td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($cliente['telefono']); ?></td>
                                <td class="px-6 py-4">
                                    <button onclick="abrirModalEditar(
                                        <?php echo $cliente['id_cliente']; ?>,
                                        '<?php echo htmlspecialchars(addslashes($cliente['nombre_completo'])); ?>',
                                        '<?php echo htmlspecialchars(addslashes($cliente['correo'])); ?>',
                                        '<?php echo htmlspecialchars(addslashes($cliente['telefono'])); ?>'
                                    )" class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 mr-2">
                                        Editar
                                    </button>
                                    <button onclick="abrirModalSolicitud(<?php echo $cliente['id_cliente']; ?>, '<?php echo htmlspecialchars(addslashes($cliente['nombre_completo'])); ?>')" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                        Solicitar eliminación
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal para solicitar eliminación de cliente -->
    <div id="modalSolicitud" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md relative">
            <button onclick="cerrarModalSolicitud()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
            <h2 class="text-xl font-semibold mb-4">Solicitar eliminación de cliente</h2>
            <form method="POST" autocomplete="off">
                <input type="hidden" name="id_cliente_eliminar" id="id_cliente_eliminar">
                <div class="mb-4">
                    <label class="block text-sm font-medium">Cliente</label>
                    <input type="text" id="nombre_cliente_eliminar" class="w-full border px-3 py-2 rounded bg-gray-100" readonly>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium">Motivo</label>
                    <textarea name="motivo_eliminacion" required class="w-full border px-3 py-2 rounded"></textarea>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="submit" name="solicitar_eliminacion" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                        Enviar solicitud
                    </button>
                    <button type="button" onclick="cerrarModalSolicitud()" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal para editar cliente -->
    <div id="modalEditar" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md relative">
            <button onclick="cerrarModalEditar()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
            <h2 class="text-xl font-semibold mb-4">Editar Cliente</h2>
            <form method="POST" autocomplete="off">
                <input type="hidden" name="id_cliente_editar" id="id_cliente_editar">
                <div class="mb-4">
                    <label class="block text-sm font-medium">Nombre completo</label>
                    <input type="text" name="nombre_completo_editar" id="nombre_completo_editar" required class="w-full border px-3 py-2 rounded" />
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium">Correo</label>
                    <input type="email" name="correo_editar" id="correo_editar" required class="w-full border px-3 py-2 rounded" />
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium">Teléfono</label>
                    <input type="text" name="telefono_editar" id="telefono_editar" required class="w-full border px-3 py-2 rounded" />
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="submit" name="editar_cliente" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">
                        Guardar cambios
                    </button>
                    <button type="button" onclick="cerrarModalEditar()" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function abrirModalSolicitud(id, nombre) {
        document.getElementById('id_cliente_eliminar').value = id;
        document.getElementById('nombre_cliente_eliminar').value = nombre;
        document.getElementById('modalSolicitud').classList.remove('hidden');
    }
    function cerrarModalSolicitud() {
        document.getElementById('modalSolicitud').classList.add('hidden');
    }

    function abrirModalEditar(id, nombre, correo, telefono) {
        document.getElementById('id_cliente_editar').value = id;
        document.getElementById('nombre_completo_editar').value = nombre;
        document.getElementById('correo_editar').value = correo;
        document.getElementById('telefono_editar').value = telefono;
        document.getElementById('modalEditar').classList.remove('hidden');
    }
    function cerrarModalEditar() {
        document.getElementById('modalEditar').classList.add('hidden');
    }
    </script>
</body>
</html>