-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS acoeemprendedores;
USE acoeemprendedores;

-- Tabla: empleados
CREATE TABLE empleados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo_empleado VARCHAR(20) UNIQUE NOT NULL,
    nombre_completo VARCHAR(255) NOT NULL,
    estado_familiar ENUM('Soltero', 'Casado', 'Divorciado', 'Viudo') NOT NULL,
    documento_identidad VARCHAR(20) UNIQUE NOT NULL,
    fecha_nacimiento DATE NOT NULL,
    edad INT GENERATED ALWAYS AS (YEAR(CURDATE()) - YEAR(fecha_nacimiento)) VIRTUAL,
    direccion TEXT NOT NULL,
    puesto VARCHAR(100) NOT NULL,
    departamento ENUM('Finanzas', 'Atención al Cliente', 'Gerencia', 'Servicios Varios', 'Seguridad') NOT NULL,
    sueldo DECIMAL(10,2) NOT NULL,
    profesion VARCHAR(100) NOT NULL,
    correo VARCHAR(100),
    telefono VARCHAR(15)
);

-- Tabla: clientes
CREATE TABLE clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_completo VARCHAR(255) NOT NULL,
    documento_identidad VARCHAR(20) UNIQUE NOT NULL,
    fecha_nacimiento DATE NOT NULL,
    edad INT GENERATED ALWAYS AS (YEAR(CURDATE()) - YEAR(fecha_nacimiento)) VIRTUAL,
    direccion TEXT NOT NULL,
    estado_familiar ENUM('Soltero', 'Casado', 'Divorciado', 'Viudo') NOT NULL,
    profesion VARCHAR(100),
    correo VARCHAR(100),
    telefono VARCHAR(15),
    lugar_trabajo VARCHAR(255),
    direccion_trabajo TEXT,
    salario_mensual DECIMAL(10,2),
    otros_ingresos DECIMAL(10,2)
);

-- Tabla: cuentas
CREATE TABLE cuentas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    numero_cuenta VARCHAR(20) UNIQUE NOT NULL,
    fecha_apertura DATE NOT NULL,
    monto_apertura DECIMAL(10,2) NOT NULL,
    fecha_cierre DATE,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE
);

-- Tabla: tarjetas
CREATE TABLE tarjetas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    numero_tarjeta VARCHAR(16) UNIQUE NOT NULL,
    fecha_adquisicion DATE NOT NULL,
    limite_monto DECIMAL(10,2) NOT NULL,
    fecha_cierre DATE,
    tipo_red ENUM('Visa', 'MasterCard') NOT NULL,
    categoria ENUM('Clásica', 'Oro', 'Platinum', 'Infinite', 'Empresarial') NOT NULL,
    tasa_interes DECIMAL(5,2) NOT NULL,
    costo_membresia DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE
);

-- Tabla: prestamos
CREATE TABLE prestamos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    numero_referencia VARCHAR(20) UNIQUE NOT NULL,
    fecha_adquisicion DATE NOT NULL,
    monto_otorgado DECIMAL(10,2) NOT NULL,
    plazos_pago INT NOT NULL,
    cuota DECIMAL(10,2) NOT NULL,
    fecha_limite_pago DATE NOT NULL,
    tasa_interes DECIMAL(5,2) NOT NULL,
    cuota_seguro DECIMAL(10,2),
    categoria ENUM('Personal', 'Agropecuario', 'Hipotecario') NOT NULL,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE
);

-- Tabla: seguros
CREATE TABLE seguros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    numero_referencia VARCHAR(20) UNIQUE NOT NULL,
    categoria ENUM('Vida', 'Salud', 'Asistencia') NOT NULL,
    monto_asegurado DECIMAL(10,2) NOT NULL,
    fecha_contratacion DATE NOT NULL,
    fecha_finalizacion DATE,
    plazo_pago ENUM('Mensual', 'Trimestral', 'Semestral', 'Anual') NOT NULL,
    monto_cuota DECIMAL(10,2) NOT NULL,
    renta_diaria DECIMAL(10,2),
    causa_aplicable TEXT,
    tipo_asistencia ENUM('Vial', 'Hogar'),
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE
);

-- Tabla: beneficiarios
CREATE TABLE beneficiarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    seguro_id INT NOT NULL,
    nombre VARCHAR(255) NOT NULL,
    parentesco VARCHAR(50) NOT NULL,
    porcentaje DECIMAL(5,2) NOT NULL,
    FOREIGN KEY (seguro_id) REFERENCES seguros(id) ON DELETE CASCADE
);

-- Tabla: transacciones
CREATE TABLE transacciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    referencia_id INT NOT NULL,
    tipo_producto ENUM('Cuenta', 'Tarjeta', 'Prestamo') NOT NULL,
    empleado_id INT NOT NULL,
    monto DECIMAL(10,2) NOT NULL,
    fecha_transaccion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (empleado_id) REFERENCES empleados(id) ON DELETE SET NULL
);

-- Tabla: usuarios
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    empleado_id INT NOT NULL,
    nombre_usuario VARCHAR(50) UNIQUE NOT NULL,
    contraseña VARCHAR(255) NOT NULL,
    rol ENUM('Admin', 'Cajero', 'Analista') NOT NULL,
    FOREIGN KEY (empleado_id) REFERENCES empleados(id) ON DELETE CASCADE
);

-- Usuario admin predefinido (contraseña: admin123)
INSERT INTO usuarios (empleado_id, nombre_usuario, contraseña, rol)
VALUES (1, 'admin', '$2y$10$XGd4MO1C9HzJkE9prp7bzeKof3FqPg8l.DW5d/cD1BZXXQ6uKiTKm', 'Admin');
