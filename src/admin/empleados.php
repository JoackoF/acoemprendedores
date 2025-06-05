<?php
session_start();
require '../database/conexion.php';

// Verificar que el usuario es un administrador
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit();
}

// Obtener lista de empleados (sin departamento)
$empleados = $pdo->query("SELECT id_empleado, codigo_empleado, nombre_completo, estado_familiar, documento_identidad, fecha_nacimiento, edad, direccion, puesto, sueldo, profesion, correo, telefono FROM empleados")->fetchAll(PDO::FETCH_ASSOC);

// Inicializar variable para mostrar credenciales
$credencialesMostrar = null;

// Procesar formulario para agregar empleado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar_empleado'])) {
    $nombre = $_POST['nombre_completo'];
    $estado = $_POST['estado_familiar'];
    $documento = $_POST['documento_identidad'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    // Calcular edad automáticamente
    $fecha_nacimiento_dt = new DateTime($fecha_nacimiento);
    $hoy = new DateTime();
    $edad = $hoy->diff($fecha_nacimiento_dt)->y;
    $direccion = $_POST['direccion'];
    $puesto = $_POST['puesto'];
    $sueldo = $_POST['sueldo'];
    $profesion = $_POST['profesion'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];

    // Generar un código de empleado (puedes ajustar la lógica)
    $codigo_empleado = 'EMP' . rand(1000, 9999);

    // Insertar empleado (sin departamento)
    $stmt = $pdo->prepare("INSERT INTO empleados (codigo_empleado, nombre_completo, estado_familiar, documento_identidad, fecha_nacimiento, edad, direccion, puesto, sueldo, profesion, correo, telefono) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$codigo_empleado, $nombre, $estado, $documento, $fecha_nacimiento, $edad, $direccion, $puesto, $sueldo, $profesion, $correo, $telefono]);
    $idEmpleado = $pdo->lastInsertId();

    // Generar usuario y contraseña aleatorios
    $username = strtolower(explode(' ', trim($nombre))[0]) . rand(100, 999);
    $password = bin2hex(random_bytes(4)); // 8 caracteres hex

    // Guardar usuario (ajusta la tabla y campos según tu estructura)
    $stmt = $pdo->prepare("INSERT INTO usuarios (id_empleado, usuario, contrasena, rol) VALUES (?, ?, ?, ?)");
    $stmt->execute([$idEmpleado, $username, password_hash($password, PASSWORD_DEFAULT), 'empleado']);

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
    $id = $_POST['id_empleado'];
    $nombre = $_POST['nombre_completo'];
    $estado = $_POST['estado_familiar'];
    $documento = $_POST['documento_identidad'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $edad = $_POST['edad'];
    $direccion = $_POST['direccion'];
    $puesto = $_POST['puesto'];
    $sueldo = $_POST['sueldo'];
    $profesion = $_POST['profesion'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];

    $stmt = $pdo->prepare("UPDATE empleados SET nombre_completo=?, estado_familiar=?, documento_identidad=?, fecha_nacimiento=?, edad=?, direccion=?, puesto=?, sueldo=?, profesion=?, correo=?, telefono=? WHERE id_empleado=?");
    $stmt->execute([$nombre, $estado, $documento, $fecha_nacimiento, $edad, $direccion, $puesto, $sueldo, $profesion, $correo, $telefono, $id]);

    header('Location: empleados.php');
    exit();
}

// Procesar solicitud para eliminar empleado
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];

    // Eliminar usuario asociado primero
    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id_empleado = ?");
    $stmt->execute([$id]);

    // Ahora sí elimina el empleado
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
            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                    <?php echo htmlspecialchars($_SESSION['error']);
                    unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>
            <h1 class="text-3xl font-semibold mb-6">Empleados</h1>

            <div class="mb-6">
                <a href="dashboard.php"
                    class="bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-700">Regresar</a>
            </div>

            <!-- Botón para agregar empleado -->
            <button onclick="mostrarFormularioAgregar()"
                class="bg-blue-500 text-white px-4 py-2 rounded-md mb-6 hover:bg-blue-600">
                Agregar Empleado
            </button>

            <!-- Modal para agregar empleado -->
            <div id="modalEmpleado"
                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
                <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-2xl relative">
                    <button onclick="cerrarModalEmpleado()"
                        class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
                    <h2 class="text-xl font-semibold mb-4">Agregar Empleado</h2>
                    <form method="POST" autocomplete="off" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium">Nombre completo</label>
                            <input type="text" name="nombre_completo" required
                                class="w-full border px-3 py-2 rounded" />
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
                            <label class="block text-sm font-medium">Documento de identidad</label>
                            <input type="text" name="documento_identidad" required
                                class="w-full border px-3 py-2 rounded" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Fecha de nacimiento</label>
                            <input type="date" name="fecha_nacimiento" required
                                class="w-full border px-3 py-2 rounded" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Edad</label>
                            <input type="number" name="edad" id="edad" min="0" required
                                class="w-full border px-3 py-2 rounded" readonly />
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Dirección</label>
                            <input type="text" name="direccion" required class="w-full border px-3 py-2 rounded" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Puesto</label>
                            <input type="text" name="puesto" required class="w-full border px-3 py-2 rounded" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Sueldo</label>
                            <input type="number" step="0.01" name="sueldo" required
                                class="w-full border px-3 py-2 rounded" />
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
                        <div class="md:col-span-2 flex justify-end space-x-2 mt-4">
                            <button type="submit" name="agregar_empleado"
                                class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                                Guardar
                            </button>
                            <button type="button" onclick="cerrarModalEmpleado()"
                                class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal para editar empleado -->
            <div id="modalEditarEmpleado"
                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
                <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-2xl relative">
                    <button onclick="cerrarModalEditarEmpleado()"
                        class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
                    <h2 class="text-xl font-semibold mb-4">Editar Empleado</h2>
                    <form method="POST" autocomplete="off" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <input type="hidden" name="id_empleado" id="editar_id_empleado">
                        <div>
                            <label class="block text-sm font-medium">Nombre completo</label>
                            <input type="text" name="nombre_completo" id="editar_nombre_completo" required
                                class="w-full border px-3 py-2 rounded" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Estado familiar</label>
                            <select name="estado_familiar" id="editar_estado_familiar" required
                                class="w-full border px-3 py-2 rounded">
                                <option value="">Seleccione</option>
                                <option value="Soltero">Soltero</option>
                                <option value="Casado">Casado</option>
                                <option value="Divorciado">Divorciado</option>
                                <option value="Viudo">Viudo</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Documento de identidad</label>
                            <input type="text" name="documento_identidad" id="editar_documento_identidad" required
                                class="w-full border px-3 py-2 rounded" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Fecha de nacimiento</label>
                            <input type="date" name="fecha_nacimiento" id="editar_fecha_nacimiento" required
                                class="w-full border px-3 py-2 rounded" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Edad</label>
                            <input type="number" name="edad" id="editar_edad" min="0" required
                                class="w-full border px-3 py-2 rounded" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Dirección</label>
                            <input type="text" name="direccion" id="editar_direccion" required
                                class="w-full border px-3 py-2 rounded" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Puesto</label>
                            <input type="text" name="puesto" id="editar_puesto" required
                                class="w-full border px-3 py-2 rounded" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Sueldo</label>
                            <input type="number" step="0.01" name="sueldo" id="editar_sueldo" required
                                class="w-full border px-3 py-2 rounded" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Profesión</label>
                            <input type="text" name="profesion" id="editar_profesion" required
                                class="w-full border px-3 py-2 rounded" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Correo</label>
                            <input type="email" name="correo" id="editar_correo" required
                                class="w-full border px-3 py-2 rounded" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Teléfono</label>
                            <input type="text" name="telefono" id="editar_telefono" required
                                class="w-full border px-3 py-2 rounded" />
                        </div>
                        <div class="md:col-span-2 flex justify-end space-x-2 mt-4">
                            <button type="submit" name="editar_empleado"
                                class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">
                                Guardar cambios
                            </button>
                            <button type="button" onclick="cerrarModalEditarEmpleado()"
                                class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabla de empleados -->
            <table class="min-w-full table-auto bg-white rounded-lg shadow-lg">
                <thead>
                    <tr>
                        <th class="px-6 py-4 text-left">Nombre</th>
                        <th class="px-6 py-4 text-left">Puesto</th>
                        <th class="px-6 py-4 text-left">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($empleados as $empleado): ?>
                        <tr>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($empleado['nombre_completo']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($empleado['puesto']); ?></td>
                            <td class="px-6 py-4">
                                <!-- Botón para editar empleado -->
                                <button onclick="abrirModalEditarEmpleado(
                                        <?php echo $empleado['id_empleado']; ?>,
                                        '<?php echo htmlspecialchars(addslashes($empleado['nombre_completo'])); ?>',
                                        '<?php echo $empleado['estado_familiar']; ?>',
                                        '<?php echo htmlspecialchars(addslashes($empleado['documento_identidad'])); ?>',
                                        '<?php echo $empleado['fecha_nacimiento']; ?>',
                                        '<?php echo $empleado['edad']; ?>',
                                        '<?php echo htmlspecialchars(addslashes($empleado['direccion'])); ?>',
                                        '<?php echo htmlspecialchars(addslashes($empleado['puesto'])); ?>',
                                        '<?php echo $empleado['sueldo']; ?>',
                                        '<?php echo htmlspecialchars(addslashes($empleado['profesion'])); ?>',
                                        '<?php echo htmlspecialchars(addslashes($empleado['correo'])); ?>',
                                        '<?php echo htmlspecialchars(addslashes($empleado['telefono'])); ?>'
                                    )" class="bg-yellow-500 text-white px-4 py-2 rounded-md hover:bg-yellow-600">
                                    Editar
                                </button>
                                <!-- Botón para eliminar empleado -->
                                <a href="empleados.php?eliminar=<?php echo $empleado['id_empleado']; ?>"
                                    class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600"
                                    onclick="return confirm('¿Estás seguro de eliminar este empleado?');">
                                    Eliminar
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Mostrar credenciales generadas -->
            <?php if ($credencialesMostrar): ?>
                <div class="bg-white p-4 rounded-lg shadow-lg mb-6">
                    <h2 class="text-xl font-semibold mb-4">Credenciales Generadas</h2>
                    <p class="text-sm text-gray-700 mb-2">Usuario:
                        <strong><?php echo htmlspecialchars($credencialesMostrar['username']); ?></strong>
                    </p>
                    <p class="text-sm text-gray-700">Contraseña:
                        <strong><?php echo htmlspecialchars($credencialesMostrar['password']); ?></strong>
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Mostrar y ocultar formularios
        function mostrarFormularioAgregar() {
            document.getElementById('modalEmpleado').classList.remove('hidden');
        }

        function cerrarModalEmpleado() {
            document.getElementById('modalEmpleado').classList.add('hidden');
        }

        function ocultarFormularioAgregar() {
            document.getElementById('formularioAgregar').style.display = 'none';
        }

        function mostrarFormularioEditar(id) {
            // Obtener datos del empleado
            const empleados = <?php echo json_encode($empleados); ?>;
            const empleado = empleados.find(e => e.id_empleado == id);
            document.getElementById('editarId').value = empleado.id_empleado;
            document.getElementById('editarNombre').value = empleado.nombre_completo;
            document.getElementById('editarPuesto').value = empleado.puesto;
            // Seleccionar el departamento actual
            const select = document.getElementById('editarDepartamento');
            for (let i = 0; i < select.options.length; i++) {
                if (select.options[i].value === empleado.departamento) {
                    select.selectedIndex = i;
                    break;
                }
            }
            document.getElementById('formularioEditar').style.display = 'block';
        }

        function cerrarModalEditarEmpleado() {
            document.getElementById('modalEditarEmpleado').classList.add('hidden');
        }

        function mostrarModalEditarEmpleado(id) {
            // Obtener datos del empleado
            const empleados = <?php echo json_encode($empleados); ?>;
            const empleado = empleados.find(e => e.id_empleado == id);

            // Llenar el formulario con los datos del empleado
            document.getElementById('editar_id_empleado').value = empleado.id_empleado;
            document.getElementById('editar_nombre_completo').value = empleado.nombre_completo;
            document.getElementById('editar_estado_familiar').value = empleado.estado_familiar;
            document.getElementById('editar_documento_identidad').value = empleado.documento_identidad;
            document.getElementById('editar_fecha_nacimiento').value = empleado.fecha_nacimiento;
            document.getElementById('editar_edad').value = empleado.edad;
            document.getElementById('editar_direccion').value = empleado.direccion;
            document.getElementById('editar_puesto').value = empleado.puesto;
            document.getElementById('editar_departamento').value = empleado.departamento;
            document.getElementById('editar_sueldo').value = empleado.sueldo;
            document.getElementById('editar_profesion').value = empleado.profesion;
            document.getElementById('editar_correo').value = empleado.correo;
            document.getElementById('editar_telefono').value = empleado.telefono;

            document.getElementById('modalEditarEmpleado').classList.remove('hidden');
        }

        function abrirModalEmpleado() {
            document.getElementById('modalEmpleado').classList.remove('hidden');
        }
        function cerrarModalEmpleado() {
            document.getElementById('modalEmpleado').classList.add('hidden');
        }
        function abrirModalEditarEmpleado(
            id, nombre, estado, documento, fecha_nacimiento, edad, direccion, puesto, sueldo, profesion, correo, telefono
        ) {
            document.getElementById('editar_id_empleado').value = id;
            document.getElementById('editar_nombre_completo').value = nombre;
            document.getElementById('editar_estado_familiar').value = estado;
            document.getElementById('editar_documento_identidad').value = documento;
            document.getElementById('editar_fecha_nacimiento').value = fecha_nacimiento;
            document.getElementById('editar_edad').value = edad;
            document.getElementById('editar_direccion').value = direccion;
            document.getElementById('editar_puesto').value = puesto;
            document.getElementById('editar_sueldo').value = sueldo;
            document.getElementById('editar_profesion').value = profesion;
            document.getElementById('editar_correo').value = correo;
            document.getElementById('editar_telefono').value = telefono;
            document.getElementById('modalEditarEmpleado').classList.remove('hidden');
        }
        function cerrarModalEditarEmpleado() {
            document.getElementById('modalEditarEmpleado').classList.add('hidden');
        }

        // Calcular edad automáticamente al seleccionar la fecha de nacimiento
        document.addEventListener('DOMContentLoaded', function () {
            const fechaNacimientoInput = document.querySelector('input[name="fecha_nacimiento"]');
            const edadInput = document.getElementById('edad');
            if (fechaNacimientoInput && edadInput) {
                fechaNacimientoInput.addEventListener('change', function () {
                    const hoy = new Date();
                    const nacimiento = new Date(this.value);
                    let edad = hoy.getFullYear() - nacimiento.getFullYear();
                    const m = hoy.getMonth() - nacimiento.getMonth();
                    if (m < 0 || (m === 0 && hoy.getDate() < nacimiento.getDate())) {
                        edad--;
                    }
                    edadInput.value = isNaN(edad) ? '' : edad;
                });
            }
        });
    </script>
</body>

</html>