<?php
session_start();
require 'conexion.php';

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: login.php');
    exit();
}

$productos = $pdo->query("SELECT pf.id_producto, pf.tipo_producto, pf.detalle_producto, c.nombre_completo 
                          FROM productos_financieros pf
                          JOIN clientes c ON pf.id_cliente = c.id_cliente")->fetchAll(PDO::FETCH_ASSOC);

$clientes = $pdo->query("SELECT id_cliente, nombre_completo FROM clientes")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar_producto'])) {
    $tipo_producto = $_POST['tipo_producto'];
    $detalle_producto = $_POST['detalle_producto'];
    $id_cliente = $_POST['id_cliente'];

    $stmt = $pdo->prepare("INSERT INTO productos_financieros (tipo_producto, detalle_producto, id_cliente) VALUES (?, ?, ?)");
    $stmt->execute([$tipo_producto, $detalle_producto, $id_cliente]);

    header('Location: productos_financieros.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_producto'])) {
    $id_producto = $_POST['id_producto'];
    $tipo_producto = $_POST['tipo_producto'];
    $detalle_producto = $_POST['detalle_producto'];
    $id_cliente = $_POST['id_cliente'];

    $stmt = $pdo->prepare("UPDATE productos_financieros SET tipo_producto = ?, detalle_producto = ?, id_cliente = ? WHERE id_producto = ?");
    $stmt->execute([$tipo_producto, $detalle_producto, $id_cliente, $id_producto]);

    header('Location: productos_financieros.php');
    exit();
}

if (isset($_GET['eliminar'])) {
    $id_producto = $_GET['eliminar'];

    $stmt = $pdo->prepare("DELETE FROM productos_financieros WHERE id_producto = ?");
    $stmt->execute([$id_producto]);

    header('Location: productos_financieros.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos Financieros - ACOEMPRENDEDORES</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex">
        <?php include 'sidebar.php'; ?>

        <div class="flex-1 p-6">
            <h1 class="text-3xl font-semibold mb-6">Productos Financieros</h1>

            <div class="mb-6">
                <a href="dashboard.php" class="bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-700">Regresar</a>
            </div>

            <button onclick="mostrarFormularioAgregar()" class="bg-blue-500 text-white px-4 py-2 rounded-md mb-6 hover:bg-blue-600">
                Agregar Producto Financiero
            </button>

            <div id="formularioAgregar" class="hidden mb-6">
                <form method="POST" class="bg-white p-6 rounded-lg shadow-lg">
                    <h2 class="text-xl font-semibold mb-4">Agregar Producto Financiero</h2>
                    <div class="mb-4">
                        <label for="tipo_producto" class="block text-sm font-medium text-gray-700">Tipo de Producto</label>
                        <select name="tipo_producto" id="tipo_producto" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md" required>
                            <option value="Cuenta">Cuenta</option>
                            <option value="Tarjeta">Tarjeta</option>
                            <option value="Prestamo">Préstamo</option>
                            <option value="Seguro">Seguro</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="detalle_producto" class="block text-sm font-medium text-gray-700">Detalles</label>
                        <textarea name="detalle_producto" id="detalle_producto" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md" required></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="id_cliente" class="block text-sm font-medium text-gray-700">Cliente</label>
                        <select name="id_cliente" id="id_cliente" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md" required>
                            <?php foreach ($clientes as $cliente): ?>
                                <option value="<?php echo $cliente['id_cliente']; ?>"><?php echo htmlspecialchars($cliente['nombre_completo']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" name="agregar_producto" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">
                        Guardar
                    </button>
                    <button type="button" onclick="ocultarFormularioAgregar()" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                        Cancelar
                    </button>
                </form>
            </div>

            <table class="min-w-full table-auto bg-white rounded-lg shadow-lg">
                <thead>
                    <tr>
                        <th class="px-6 py-4 text-left">Tipo Producto</th>
                        <th class="px-6 py-4 text-left">Cliente</th>
                        <th class="px-6 py-4 text-left">Detalles</th>
                        <th class="px-6 py-4 text-left">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productos as $producto): ?>
                        <tr>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($producto['tipo_producto']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($producto['nombre_completo']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($producto['detalle_producto']); ?></td>
                            <td class="px-6 py-4">
                                <button onclick="mostrarFormularioEditar(<?php echo $producto['id_producto']; ?>)" class="bg-yellow-500 text-white px-4 py-2 rounded-md hover:bg-yellow-600">
                                    Editar
                                </button>
                                <a href="productos_financieros.php?eliminar=<?php echo $producto['id_producto']; ?>" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600" onclick="return confirm('¿Estás seguro de eliminar este producto financiero?');">
                                    Eliminar
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div id="formularioEditar" class="hidden mb-6">
                <form method="POST" class="bg-white p-6 rounded-lg shadow-lg">
                    <h2 class="text-xl font-semibold mb-4">Editar Producto Financiero</h2>
                    <input type="hidden" name="id_producto" id="editarIdProducto">
                    <div class="mb-4">
                        <label for="editarTipoProducto" class="block text-sm font-medium text-gray-700">Tipo de Producto</label>
                        <select name="tipo_producto" id="editarTipoProducto" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md" required>
                            <option value="Cuenta">Cuenta</option>
                            <option value="Tarjeta">Tarjeta</option>
                            <option value="Prestamo">Préstamo</option>
                            <option value="Seguro">Seguro</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="editarDetalleProducto" class="block text-sm font-medium text-gray-700">Detalles</label>
                        <textarea name="detalle_producto" id="editarDetalleProducto" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md" required></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="editarIdCliente" class="block text-sm font-medium text-gray-700">Cliente</label>
                        <select name="id_cliente" id="editarIdCliente" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md" required>
                            <?php foreach ($clientes as $cliente): ?>
                                <option value="<?php echo $cliente['id_cliente']; ?>"><?php echo htmlspecialchars($cliente['nombre_completo']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" name="editar_producto" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">
                        Guardar
                    </button>
                    <button type="button" onclick="ocultarFormularioEditar()" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                        Cancelar
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function mostrarFormularioAgregar() {
            document.getElementById('formularioAgregar').style.display = 'block';
        }

        function ocultarFormularioAgregar() {
            document.getElementById('formularioAgregar').style.display = 'none';
        }

        function mostrarFormularioEditar(id) {
            const producto = <?php echo json_encode($productos); ?>.find(p => p.id_producto == id);
            document.getElementById('editarIdProducto').value = producto.id_producto;
            document.getElementById('editarTipoProducto').value = producto.tipo_producto;
            document.getElementById('editarDetalleProducto').value = producto.detalle_producto;
            document.getElementById('editarIdCliente').value = producto.id_cliente;
            document.getElementById('formularioEditar').style.display = 'block';
        }

        function ocultarFormularioEditar() {
            document.getElementById('formularioEditar').style.display = 'none';
        }
    </script>
</body>
</html>