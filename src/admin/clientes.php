<?php
session_start();
require '../database/conexion.php';

// Verificar que el usuario es un administrador
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit();
}

// AGREGAR CLIENTE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar_cliente'])) {
    $nombre = $_POST['nombre_completo'];
    $documento = $_POST['documento_identidad'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $edad = $_POST['edad'];
    $direccion = $_POST['direccion'];
    $estado = $_POST['estado_familiar'];
    $profesion = $_POST['profesion'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $lugar_trabajo = $_POST['lugar_trabajo'];
    $direccion_trabajo = $_POST['direccion_trabajo'];
    $salario_mensual = $_POST['salario_mensual'];
    $otros_ingresos = $_POST['otros_ingresos'];

    $stmt = $pdo->prepare("INSERT INTO clientes 
        (nombre_completo, documento_identidad, fecha_nacimiento, edad, direccion, estado_familiar, profesion, correo, telefono, lugar_trabajo, direccion_trabajo, salario_mensual, otros_ingresos)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $nombre, $documento, $fecha_nacimiento, $edad, $direccion, $estado, $profesion, $correo, $telefono, $lugar_trabajo, $direccion_trabajo, $salario_mensual, $otros_ingresos
    ]);
    header('Location: clientes.php');
    exit();
}

// EDITAR CLIENTE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_cliente'])) {
    $id = $_POST['id_cliente'];
    $nombre = $_POST['nombre_completo'];
    $documento = $_POST['documento_identidad'];
    $estado = $_POST['estado_familiar'];
    $stmt = $pdo->prepare("UPDATE clientes SET nombre_completo=?, documento_identidad=?, estado_familiar=? WHERE id_cliente=?");
    $stmt->execute([$nombre, $documento, $estado, $id]);
    header('Location: clientes.php');
    exit();
}

// ELIMINAR CLIENTE
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $stmt = $pdo->prepare("DELETE FROM clientes WHERE id_cliente=?");
    $stmt->execute([$id]);
    header('Location: clientes.php');
    exit();
}

// Obtener lista de clientes
$clientes = $pdo->query("SELECT * FROM clientes")->fetchAll(PDO::FETCH_ASSOC);
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
        <?php include '../partials/sidebar.php'; ?>
        <!-- Main content -->
        <div class="flex-1 p-6">
            <!-- Formulario para agregar cliente -->
            <div class="mb-6">
                <!-- Botón para abrir el modal de agregar cliente -->
                <button onclick="abrirModalCliente()"
                    class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 mb-2">Agregar
                    Cliente</button>
                <form id="formAgregar" method="POST" class="bg-white p-6 rounded-lg shadow-lg mb-4 hidden">
                    <h2 class="text-xl font-semibold mb-4">Nuevo Cliente</h2>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Nombre</label>
                        <input type="text" name="nombre_completo"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Documento</label>
                        <input type="text" name="documento_identidad"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Estado Civil</label>
                        <input type="text" name="estado_familiar"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md" required>
                    </div>
                    <button type="submit" name="agregar_cliente"
                        class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Guardar</button>
                    <button type="button" onclick="document.getElementById('formAgregar').classList.add('hidden')"
                        class="ml-2 px-4 py-2 rounded-md border">Cancelar</button>
                </form>
            </div>
            <h1 class="text-3xl font-semibold mb-6">Clientes</h1>

            <div class="mb-6">
                <a href="dashboard.php"
                    class="bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-700">Regresar</a>
            </div>

            <table class="min-w-full table-auto bg-white rounded-lg shadow-lg">
                <thead>
                    <tr>
                        <th class="px-6 py-4 text-left">Nombre</th>
                        <th class="px-6 py-4 text-left">Documento</th>
                        <th class="px-6 py-4 text-left">Estado Civil</th>
                        <th class="px-6 py-4 text-left">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clientes as $cliente): ?>
                        <tr>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($cliente['nombre_completo']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($cliente['documento_identidad']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($cliente['estado_familiar']); ?></td>
                            <td class="px-6 py-4">
                                <!-- Botón Editar -->
                                <button
                                    onclick="editarCliente('<?php echo $cliente['id_cliente']; ?>', '<?php echo htmlspecialchars($cliente['nombre_completo'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($cliente['documento_identidad'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($cliente['estado_familiar'], ENT_QUOTES); ?>')"
                                    class="bg-yellow-400 text-white px-3 py-1 rounded hover:bg-yellow-500">Editar</button>
                                <!-- Botón Eliminar -->
                                <a href="?eliminar=<?php echo $cliente['id_cliente']; ?>"
                                    onclick="return confirm('¿Seguro que deseas eliminar este cliente?')"
                                    class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 ml-2">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Modal para agregar cliente -->
            <div id="modalCliente" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
                <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-2xl relative">
                    <button onclick="cerrarModalCliente()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
                    <h2 class="text-xl font-semibold mb-4">Nuevo Cliente</h2>
                    <form method="POST" autocomplete="off" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium">Nombre completo</label>
                            <input type="text" name="nombre_completo" required class="w-full border px-3 py-2 rounded" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Documento de identidad</label>
                            <input type="text" name="documento_identidad" required class="w-full border px-3 py-2 rounded" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Fecha de nacimiento</label>
                            <input type="date" name="fecha_nacimiento" required class="w-full border px-3 py-2 rounded" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Edad</label>
                            <input type="number" name="edad" min="0" required class="w-full border px-3 py-2 rounded" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Dirección</label>
                            <input type="text" name="direccion" required class="w-full border px-3 py-2 rounded" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Estado familiar</label>
                            <select name="estado_familiar" required class="w-full border px-3 py-2 rounded">
                                <option value="">Seleccione</option>
                                <option value="Soltero">Soltero</option>
                                <option value="Casado">Casado</option>
                                <option value="Divorciado">Divorciado</option>
                                <option value="Viudo">Viudo</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Profesión</label>
                            <input type="text" name="profesion" required class="w-full border px-3 py-2 rounded" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Correo</label>
                            <input type="email" name="correo" required class="w-full border px-3 py-2 rounded" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Teléfono</label>
                            <input type="text" name="telefono" required class="w-full border px-3 py-2 rounded" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Lugar de trabajo</label>
                            <input type="text" name="lugar_trabajo" class="w-full border px-3 py-2 rounded" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Dirección de trabajo</label>
                            <input type="text" name="direccion_trabajo" class="w-full border px-3 py-2 rounded" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Salario mensual</label>
                            <input type="number" step="0.01" name="salario_mensual" class="w-full border px-3 py-2 rounded" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Otros ingresos</label>
                            <input type="number" step="0.01" name="otros_ingresos" class="w-full border px-3 py-2 rounded" />
                        </div>
                        <div class="md:col-span-2 flex justify-end space-x-2 mt-4">
                            <button type="submit" name="agregar_cliente" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                Guardar
                            </button>
                            <button type="button" onclick="cerrarModalCliente()" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
function abrirModalCliente() {
    document.getElementById('modalCliente').classList.remove('hidden');
}
function cerrarModalCliente() {
    document.getElementById('modalCliente').classList.add('hidden');
}
</script>
</body>

</html>