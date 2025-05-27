<?php
session_start();
require '../../database/conexion.php';

// Verificar que el usuario es un administrador
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: ../../auth/login.php');
    exit();
}

// AGREGAR CLIENTE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar_cliente'])) {
    $nombre = $_POST['nombre_completo'];
    $documento = $_POST['documento_identidad'];
    $estado = $_POST['estado_familiar'];
    $stmt = $pdo->prepare("INSERT INTO clientes (nombre_completo, documento_identidad, estado_familiar) VALUES (?, ?, ?)");
    $stmt->execute([$nombre, $documento, $estado]);
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
                <button onclick="document.getElementById('formAgregar').classList.remove('hidden')"
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
        </div>
    </div>
</body>

</html>