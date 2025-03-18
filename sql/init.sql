-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS acoeemprendedores;
USE acoeemprendedores;

-- Tabla de empleados
CREATE TABLE empleados (
    id_empleado INT AUTO_INCREMENT PRIMARY KEY,
    codigo_empleado VARCHAR(20) UNIQUE NOT NULL,
    nombre_completo VARCHAR(100) NOT NULL,
    estado_familiar ENUM('Soltero', 'Casado', 'Divorciado', 'Viudo') NOT NULL,
    documento_identidad VARCHAR(20) UNIQUE NOT NULL,
    fecha_nacimiento DATE NOT NULL,
    edad INT NOT NULL,
    direccion TEXT NOT NULL,
    puesto VARCHAR(50) NOT NULL,
    departamento ENUM('Finanzas', 'Atención al cliente', 'Gerencia', 'Servicios varios', 'Seguridad') NOT NULL,
    sueldo DECIMAL(10, 2) NOT NULL,
    profesion VARCHAR(50),
    correo VARCHAR(100) NOT NULL,
    telefono VARCHAR(15) NOT NULL
);

-- Tabla de clientes
CREATE TABLE clientes (
    id_cliente INT AUTO_INCREMENT PRIMARY KEY,
    nombre_completo VARCHAR(100) NOT NULL,
    documento_identidad VARCHAR(20) UNIQUE NOT NULL,
    fecha_nacimiento DATE NOT NULL,
    edad INT NOT NULL,
    direccion TEXT NOT NULL,
    estado_familiar ENUM('Soltero', 'Casado', 'Divorciado', 'Viudo') NOT NULL,
    profesion VARCHAR(50),
    correo VARCHAR(100) NOT NULL,
    telefono VARCHAR(15) NOT NULL,
    lugar_trabajo VARCHAR(100),
    direccion_trabajo TEXT,
    salario_mensual DECIMAL(10, 2),
    otros_ingresos DECIMAL(10, 2)
);

-- Tabla de productos financieros
CREATE TABLE productos_financieros (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT NOT NULL,
    tipo_producto ENUM('Cuenta', 'Tarjeta', 'Prestamo', 'Seguro') NOT NULL,
    detalle_producto TEXT NOT NULL,
    fecha_adquisicion DATE NOT NULL,
    fecha_cierre DATE,
    FOREIGN KEY (id_cliente) REFERENCES clientes(id_cliente)
);

-- Tabla de cuentas (Ahorro o Corriente)
CREATE TABLE cuentas (
    id_cuenta INT AUTO_INCREMENT PRIMARY KEY,
    id_producto INT UNIQUE NOT NULL,
    numero_cuenta VARCHAR(20) UNIQUE NOT NULL,
    monto_apertura DECIMAL(12, 2) NOT NULL,
    FOREIGN KEY (id_producto) REFERENCES productos_financieros(id_producto)
);

-- Tabla de tarjetas (Débito o Crédito)
CREATE TABLE tarjetas (
    id_tarjeta INT AUTO_INCREMENT PRIMARY KEY,
    id_producto INT UNIQUE NOT NULL,
    numero_tarjeta VARCHAR(20) UNIQUE NOT NULL,
    limite_monto DECIMAL(12, 2) NOT NULL,
    tipo_red ENUM('Visa', 'MasterCard') NOT NULL,
    categoria ENUM('Clasica', 'Infinite', 'Oro', 'Platinum', 'Empresarial') NOT NULL,
    tasa_interes DECIMAL(5, 2) NOT NULL,
    costo_membresia DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (id_producto) REFERENCES productos_financieros(id_producto)
);

-- Tabla de préstamos (Personal, Agropecuario o Hipotecario)
CREATE TABLE prestamos (
    id_prestamo INT AUTO_INCREMENT PRIMARY KEY,
    id_producto INT UNIQUE NOT NULL,
    numero_referencia VARCHAR(20) UNIQUE NOT NULL,
    monto_otorgado DECIMAL(12, 2) NOT NULL,
    plazo_pago INT NOT NULL,
    cuota DECIMAL(12, 2) NOT NULL,
    tasa_interes DECIMAL(5, 2) NOT NULL,
    cuota_seguro DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (id_producto) REFERENCES productos_financieros(id_producto)
);

-- Tabla de seguros (Vida, Salud o Asistencia)
CREATE TABLE seguros (
    id_seguro INT AUTO_INCREMENT PRIMARY KEY,
    id_producto INT UNIQUE NOT NULL,
    categoria ENUM('Vida', 'Salud', 'Asistencia') NOT NULL,
    monto_asegurado DECIMAL(12, 2) NOT NULL,
    plazo_pago ENUM('Mensual', 'Trimestral', 'Semestral', 'Anual') NOT NULL,
    cuota DECIMAL(10, 2) NOT NULL,
    renta_hospitalizacion DECIMAL(10, 2),
    causas TEXT,
    FOREIGN KEY (id_producto) REFERENCES productos_financieros(id_producto)
);

-- Tabla de transacciones
CREATE TABLE transacciones (
    id_transaccion INT AUTO_INCREMENT PRIMARY KEY,
    id_producto INT NOT NULL,
    id_empleado INT NOT NULL,
    monto DECIMAL(12, 2) NOT NULL,
    fecha_transaccion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_producto) REFERENCES productos_financieros(id_producto),
    FOREIGN KEY (id_empleado) REFERENCES empleados(id_empleado)
);

-- Tabla de usuarios (ligados a empleados)
CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    id_empleado INT UNIQUE NOT NULL,
    usuario VARCHAR(50) UNIQUE NOT NULL,
    clave VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'cajero', 'gerente') NOT NULL,
    FOREIGN KEY (id_empleado) REFERENCES empleados(id_empleado)
);
