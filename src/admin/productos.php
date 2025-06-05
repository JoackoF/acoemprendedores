<?php
session_start();
require '../database/conexion.php';

// Verificar que el usuario es un administrador
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit();
}

// Obtener lista de productos financieros
$productos = $pdo->query("SELECT pf.id_producto, pf.tipo_producto, pf.detalle_producto, c.nombre_completo 
                          FROM productos_financieros pf
                          JOIN clientes c ON pf.id_cliente = c.id_cliente")->fetchAll(PDO::FETCH_ASSOC);

// Obtener lista de clientes para el formulario de agregar/editar
$clientes = $pdo->query("SELECT id_cliente, nombre_completo FROM clientes")->fetchAll(PDO::FETCH_ASSOC);

// Procesar formulario para agregar producto financiero
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar_producto'])) {
    $tipo_producto = $_POST['tipo_producto'];
    $detalle_producto = $_POST['detalle_producto'];
    $id_cliente = $_POST['id_cliente'];
    $fecha_adquisicion = date('Y-m-d'); // Fecha actual

    $stmt = $pdo->prepare("INSERT INTO productos_financieros (tipo_producto, detalle_producto, id_cliente, fecha_adquisicion) VALUES (?, ?, ?, ?)");
    $stmt->execute([$tipo_producto, $detalle_producto, $id_cliente, $fecha_adquisicion]);

    header('Location: productos.php');
    exit();
}

// Procesar formulario para editar producto financiero
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_producto'])) {
    $id_producto = $_POST['id_producto'];
    $tipo_producto = $_POST['tipo_producto'];
    $detalle_producto = $_POST['detalle_producto'];
    $id_cliente = $_POST['id_cliente'];

    $stmt = $pdo->prepare("UPDATE productos_financieros SET tipo_producto = ?, detalle_producto = ?, id_cliente = ? WHERE id_producto = ?");
    $stmt->execute([$tipo_producto, $detalle_producto, $id_cliente, $id_producto]);

    header('Location: productos.php');
    exit();
}

// Procesar solicitud para eliminar producto financiero
if (isset($_GET['eliminar'])) {
    $id_producto = $_GET['eliminar'];

    $stmt = $pdo->prepare("DELETE FROM productos_financieros WHERE id_producto = ?");
    $stmt->execute([$id_producto]);

    header('Location: productos.php');
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
        <!-- Sidebar -->
        <?php include '../partials/sidebar.php'; ?>

        <!-- Main content -->
        <div class="flex-1 p-6">
            <h1 class="text-3xl font-semibold mb-6">Productos Financieros</h1>

            <div class="mb-6">
                <a href="dashboard.php"
                    class="bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-700">Regresar</a>
            </div>

            <!-- Botón para agregar producto financiero -->
            <button onclick="mostrarFormularioAgregar()"
                class="bg-blue-500 text-white px-4 py-2 rounded-md mb-6 hover:bg-blue-600">
                Agregar Producto Financiero
            </button>

            <!-- Formulario para agregar producto financiero (oculto por defecto) -->
            <div id="formularioAgregar" class="hidden mb-6">
                <form method="POST" class="bg-white p-6 rounded-lg shadow-lg">
                    <h2 class="text-xl font-semibold mb-4">Agregar Producto Financiero</h2>
                    <div class="mb-4">
                        <label for="tipo_producto" class="block text-sm font-medium text-gray-700">Tipo de Producto</label>
                        <select name="tipo_producto" id="tipo_producto"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md" required
                            onchange="mostrarCamposPorTipo()">
                            <option value="">Seleccione</option>
                            <option value="Cuenta">Cuenta</option>
                            <option value="Tarjeta">Tarjeta</option>
                            <option value="Prestamo">Préstamo</option>
                            <option value="Seguro">Seguro</option>
                        </select>
                    </div>
                    <!-- Campos comunes -->
                    <div class="mb-4">
                        <label for="id_cliente" class="block text-sm font-medium text-gray-700">Cliente</label>
                        <select name="id_cliente" id="id_cliente"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md" required>
                            <?php foreach ($clientes as $cliente): ?>
                                <option value="<?php echo $cliente['id_cliente']; ?>">
                                    <?php echo htmlspecialchars($cliente['nombre_completo']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <!-- Campos específicos por tipo -->
                    <div id="camposCuenta" class="mb-4 hidden">
                        <label class="block text-sm font-medium text-gray-700">Monto de apertura</label>
                        <input type="number" name="monto_apertura" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                    </div>
                    <div id="camposTarjeta" class="mb-4 hidden">
                        <label class="block text-sm font-medium text-gray-700">Red</label>
                        <select id="tipo_red" name="tipo_red" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md" onchange="generarNumeroTarjeta()">
                            <option value="">Seleccione</option>
                            <option value="Visa">Visa</option>
                            <option value="MasterCard">MasterCard</option>
                        </select>
                        <label class="block text-sm font-medium text-gray-700 mt-2">Número de tarjeta</label>
                        <input type="text" id="numero_tarjeta" name="numero_tarjeta" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md" readonly>
                        <!-- Otros campos de tarjeta aquí -->
                    </div>
                    <div id="camposPrestamo" class="mb-4 hidden">
                        <label class="block text-sm font-medium text-gray-700">Monto otorgado</label>
                        <input type="number" name="monto_otorgado" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                        <!-- Otros campos de préstamo aquí -->
                    </div>
                    <div id="camposSeguro" class="mb-4 hidden">
                        <label class="block text-sm font-medium text-gray-700">Monto asegurado</label>
                        <input type="number" name="monto_asegurado" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                        <!-- Otros campos de seguro aquí -->
                    </div>
                    <div class="mb-4">
                        <label for="detalle_producto" class="block text-sm font-medium text-gray-700">Detalles</label>
                        <textarea name="detalle_producto" id="detalle_producto"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md" required></textarea>
                    </div>
                    <button type="submit" name="agregar_producto"
                        class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">
                        Guardar
                    </button>
                    <button type="button" onclick="ocultarFormularioAgregar()"
                        class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                        Cancelar
                    </button>
                </form>
            </div>

            <!-- Tabla de productos financieros -->
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
                                <!-- Botón para editar producto financiero -->
                                <button onclick="mostrarFormularioEditar(<?php echo $producto['id_producto']; ?>)"
                                    class="bg-yellow-500 text-white px-4 py-2 rounded-md hover:bg-yellow-600">
                                    Editar
                                </button>
                                <!-- Botón para eliminar producto financiero -->
                                <a href="productos.php?eliminar=<?php echo $producto['id_producto']; ?>"
                                    class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600"
                                    onclick="return confirm('¿Estás seguro de eliminar este producto financiero?');">
                                    Eliminar
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Formulario para editar producto financiero (oculto por defecto) -->
            <div id="formularioEditar" class="hidden mb-6">
                <form method="POST" class="bg-white p-6 rounded-lg shadow-lg">
                    <h2 class="text-xl font-semibold mb-4">Editar Producto Financiero</h2>
                    <input type="hidden" name="id_producto" id="editarIdProducto">
                    <div class="mb-4">
                        <label for="editarTipoProducto" class="block text-sm font-medium text-gray-700">Tipo de
                            Producto</label>
                        <select name="tipo_producto" id="editarTipoProducto"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md" required>
                            <option value="Cuenta">Cuenta</option>
                            <option value="Tarjeta">Tarjeta</option>
                            <option value="Prestamo">Préstamo</option>
                            <option value="Seguro">Seguro</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="editarDetalleProducto"
                            class="block text-sm font-medium text-gray-700">Detalles</label>
                        <textarea name="detalle_producto" id="editarDetalleProducto"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md" required></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="editarIdCliente" class="block text-sm font-medium text-gray-700">Cliente</label>
                        <select name="id_cliente" id="editarIdCliente"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md" required>
                            <?php foreach ($clientes as $cliente): ?>
                                <option value="<?php echo $cliente['id_cliente']; ?>">
                                    <?php echo htmlspecialchars($cliente['nombre_completo']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" name="editar_producto"
                        class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">
                        Guardar
                    </button>
                    <button type="button" onclick="ocultarFormularioEditar()"
                        class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                        Cancelar
                    </button>
                </form>
            </div>
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
            // Obtener datos del producto financiero
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

        function mostrarCamposPorTipo() {
            const tipo = document.getElementById('tipo_producto').value;
            document.getElementById('camposCuenta').style.display = tipo === 'Cuenta' ? 'block' : 'none';
            document.getElementById('camposTarjeta').style.display = tipo === 'Tarjeta' ? 'block' : 'none';
            document.getElementById('camposPrestamo').style.display = tipo === 'Prestamo' ? 'block' : 'none';
            document.getElementById('camposSeguro').style.display = tipo === 'Seguro' ? 'block' : 'none';
        }

        function generarNumeroTarjeta() {
            const red = document.getElementById('tipo_red').value;
            let numero = '';
            if (red === 'Visa') {
                numero = '4' + Math.floor(100000000000000 + Math.random() * 900000000000000); // 16 dígitos, empieza con 4
            } else if (red === 'MasterCard') {
                const prefix = ['51','52','53','54','55'][Math.floor(Math.random()*5)];
                numero = prefix + Math.floor(100000000000000 + Math.random() * 900000000000000); // 16 dígitos, empieza con 51-55
            }
            document.getElementById('numero_tarjeta').value = numero;
        }
    </script>
</body>

</html>