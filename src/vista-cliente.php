<?php
session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'cliente') {
    header('Location: login.php');
    exit();
}

require 'conexion.php';

$id_cliente = $_SESSION['usuario_id'];

$productos = $pdo->prepare("SELECT * FROM productos_financieros WHERE id_cliente = ?");
$productos->execute([$id_cliente]);
$productos = $productos->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['solicitar_producto'])) {
    $tipo_producto = $_POST['tipo_producto'];
    $detalle_solicitud = $_POST['detalle_solicitud'];

    $detalles_adicionales = [];
    if ($tipo_producto === 'Tarjeta') {
        $detalles_adicionales = [
            'tipo_tarjeta' => $_POST['tipo_tarjeta'],
            'tipo_red' => $_POST['tipo_red'],
            'categoria' => $_POST['categoria'],
        ];
    } elseif ($tipo_producto === 'Cuenta') {
        $detalles_adicionales = [
            'tipo_cuenta' => $_POST['tipo_cuenta'],
        ];
    } elseif ($tipo_producto === 'Prestamo') {
        $detalles_adicionales = [
            'monto_solicitado' => $_POST['monto_solicitado'],
            'plazo_pago' => $_POST['plazo_pago'],
        ];
    } elseif ($tipo_producto === 'Seguro') {
        $detalles_adicionales = [
            'categoria_seguro' => $_POST['categoria_seguro'],
            'monto_asegurado' => $_POST['monto_asegurado'],
        ];
    }

    $detalle_solicitud .= "\nDetalles adicionales: " . json_encode($detalles_adicionales, JSON_PRETTY_PRINT);

    $stmt = $pdo->prepare("INSERT INTO solicitudes_productos (id_cliente, tipo_producto, detalle_solicitud) VALUES (?, ?, ?)");
    $stmt->execute([$id_cliente, $tipo_producto, $detalle_solicitud]);

    $mensaje = "Nueva solicitud de producto: $tipo_producto";
    $stmt = $pdo->prepare("INSERT INTO notificaciones (id_empleado, mensaje) VALUES (1, ?)"); // Aquí puedes asignar el empleado adecuado
    $stmt->execute([$mensaje]);

    header('Location: vista_cliente.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vista Cliente - ACOEMPRENDEDORES</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function mostrarCamposAdicionales() {
            const tipoProducto = document.getElementById('tipo_producto').value;
            const camposTarjeta = document.getElementById('campos_tarjeta');
            const camposCuenta = document.getElementById('campos_cuenta');
            const camposPrestamo = document.getElementById('campos_prestamo');
            const camposSeguro = document.getElementById('campos_seguro');

            camposTarjeta.style.display = 'none';
            camposCuenta.style.display = 'none';
            camposPrestamo.style.display = 'none';
            camposSeguro.style.display = 'none';

            if (tipoProducto === 'Tarjeta') {
                camposTarjeta.style.display = 'block';
            } else if (tipoProducto === 'Cuenta') {
                camposCuenta.style.display = 'block';
            } else if (tipoProducto === 'Prestamo') {
                camposPrestamo.style.display = 'block';
            } else if (tipoProducto === 'Seguro') {
                camposSeguro.style.display = 'block';
            }
        }
    </script>
</head>
<body class="bg-gray-100">
    <div class="flex">
        <?php include 'sidebar.php'; ?>

        <div class="flex-1 p-6">
            <h1 class="text-3xl font-semibold mb-6">Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre']); ?></h1>

            <div class="mb-6">
                <a href="logout.php" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600">Cerrar Sesión</a>
            </div>

            <h2 class="text-2xl font-semibold mb-4">Mis Productos Financieros</h2>
            <div class="bg-white p-6 rounded-lg shadow-lg mb-8">
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th class="px-6 py-4 text-left">Tipo de Producto</th>
                            <th class="px-6 py-4 text-left">Detalles</th>
                            <th class="px-6 py-4 text-left">Fecha de Adquisición</th>
                            <th class="px-6 py-4 text-left">Fecha de Cierre</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($productos as $producto): ?>
                            <tr>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($producto['tipo_producto']); ?></td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($producto['detalle_producto']); ?></td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($producto['fecha_adquisicion']); ?></td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($producto['fecha_cierre']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <h2 class="text-2xl font-semibold mb-4">Solicitar Nuevo Producto</h2>
            <div class="bg-white p-6 rounded-lg shadow-lg mb-8">
                <form method="POST" onsubmit="return validarSolicitud()">
                    <div class="mb-4">
                        <label for="tipo_producto" class="block text-sm font-medium text-gray-700">Tipo de Producto</label>
                        <select name="tipo_producto" id="tipo_producto" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md" required onchange="mostrarCamposAdicionales()">
                            <option value="">Seleccione un producto</option>
                            <option value="Cuenta">Cuenta</option>
                            <option value="Tarjeta">Tarjeta</option>
                            <option value="Prestamo">Préstamo</option>
                            <option value="Seguro">Seguro</option>
                        </select>
                    </div>

                    <div id="campos_tarjeta" style="display: none;">
                        <div class="mb-4">
                            <label for="tipo_tarjeta" class="block text-sm font-medium text-gray-700">Tipo de Tarjeta</label>
                            <select name="tipo_tarjeta" id="tipo_tarjeta" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                                <option value="Debito">Débito</option>
                                <option value="Credito">Crédito</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="tipo_red" class="block text-sm font-medium text-gray-700">Tipo de Red</label>
                            <select name="tipo_red" id="tipo_red" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                                <option value="Visa">Visa</option>
                                <option value="MasterCard">MasterCard</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="categoria" class="block text-sm font-medium text-gray-700">Categoría</label>
                            <select name="categoria" id="categoria" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                                <option value="Clasica">Clásica</option>
                                <option value="Oro">Oro</option>
                                <option value="Platinum">Platinum</option>
                                <option value="Infinite">Infinite</option>
                                <option value="Empresarial">Empresarial</option>
                            </select>
                        </div>
                    </div>

                    <div id="campos_cuenta" style="display: none;">
                        <div class="mb-4">
                            <label for="tipo_cuenta" class="block text-sm font-medium text-gray-700">Tipo de Cuenta</label>
                            <select name="tipo_cuenta" id="tipo_cuenta" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                                <option value="Ahorro">Ahorro</option>
                                <option value="Corriente">Corriente</option>
                            </select>
                        </div>
                    </div>

                    <div id="campos_prestamo" style="display: none;">
                        <div class="mb-4">
                            <label for="monto_solicitado" class="block text-sm font-medium text-gray-700">Monto Solicitado</label>
                            <input type="number" step="0.01" name="monto_solicitado" id="monto_solicitado" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                        </div>
                        <div class="mb-4">
                            <label for="plazo_pago" class="block text-sm font-medium text-gray-700">Plazo de Pago (meses)</label>
                            <input type="number" name="plazo_pago" id="plazo_pago" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                        </div>
                    </div>

                    <div id="campos_seguro" style="display: none;">
                        <div class="mb-4">
                            <label for="categoria_seguro" class="block text-sm font-medium text-gray-700">Categoría de Seguro</label>
                            <select name="categoria_seguro" id="categoria_seguro" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                                <option value="Vida">Vida</option>
                                <option value="Salud">Salud</option>
                                <option value="Asistencia">Asistencia</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="monto_asegurado" class="block text-sm font-medium text-gray-700">Monto Asegurado</label>
                            <input type="number" step="0.01" name="monto_asegurado" id="monto_asegurado" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="detalle_solicitud" class="block text-sm font-medium text-gray-700">Detalles de la Solicitud</label>
                        <textarea name="detalle_solicitud" id="detalle_solicitud" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md" required></textarea>
                    </div>
                    <button type="submit" name="solicitar_producto" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">
                        Solicitar Producto
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function mostrarCamposAdicionales() {
            const tipoProducto = document.getElementById('tipo_producto').value;
            const camposTarjeta = document.getElementById('campos_tarjeta');
            const camposCuenta = document.getElementById('campos_cuenta');
            const camposPrestamo = document.getElementById('campos_prestamo');
            const camposSeguro = document.getElementById('campos_seguro');

            camposTarjeta.style.display = 'none';
            camposCuenta.style.display = 'none';
            camposPrestamo.style.display = 'none';
            camposSeguro.style.display = 'none';

            if (tipoProducto === 'Tarjeta') {
                camposTarjeta.style.display = 'block';
            } else if (tipoProducto === 'Cuenta') {
                camposCuenta.style.display = 'block';
            } else if (tipoProducto === 'Prestamo') {
                camposPrestamo.style.display = 'block';
            } else if (tipoProducto === 'Seguro') {
                camposSeguro.style.display = 'block';
            }
        }

        function validarSolicitud() {
            const tipoProducto = document.getElementById('tipo_producto').value;
            let valido = true;

            if (tipoProducto === 'Tarjeta') {
                const tipoTarjeta = document.getElementById('tipo_tarjeta').value;
                const tipoRed = document.getElementById('tipo_red').value;
                const categoria = document.getElementById('categoria').value;
                if (!tipoTarjeta || !tipoRed || !categoria) {
                    alert('Por favor, complete todos los campos de la tarjeta.');
                    valido = false;
                }
            } else if (tipoProducto === 'Cuenta') {
                const tipoCuenta = document.getElementById('tipo_cuenta').value;
                if (!tipoCuenta) {
                    alert('Por favor, seleccione el tipo de cuenta.');
                    valido = false;
                }
            } else if (tipoProducto === 'Prestamo') {
                const montoSolicitado = document.getElementById('monto_solicitado').value;
                const plazoPago = document.getElementById('plazo_pago').value;
                if (!montoSolicitado || !plazoPago) {
                    alert('Por favor, complete todos los campos del préstamo.');
                    valido = false;
                }
            } else if (tipoProducto === 'Seguro') {
                const categoriaSeguro = document.getElementById('categoria_seguro').value;
                const montoAsegurado = document.getElementById('monto_asegurado').value;
                if (!categoriaSeguro || !montoAsegurado) {
                    alert('Por favor, complete todos los campos del seguro.');
                    valido = false;
                }
            }

            return valido;
        }
    </script>
</body>
</html>