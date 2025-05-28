CREATE TABLE clientes (
    id_cliente SERIAL PRIMARY KEY,
    nombre_completo VARCHAR(100),
    documento_identidad VARCHAR(20) UNIQUE ,
    fecha_nacimiento DATE ,
    edad INTEGER ,
    direccion TEXT ,
    estado_familiar VARCHAR(15) CHECK (estado_familiar IN ('Soltero','Casado','Divorciado','Viudo')) ,
    profesion VARCHAR(50),
    correo VARCHAR(100) ,
    telefono VARCHAR(15) ,
    lugar_trabajo VARCHAR(100),
    direccion_trabajo TEXT,
    salario_mensual DECIMAL(10,2),
    otros_ingresos DECIMAL(10,2)
);

CREATE TABLE cuentas (
    id_cuenta SERIAL PRIMARY KEY,
    id_producto INTEGER UNIQUE NOT NULL,
    numero_cuenta VARCHAR(20) UNIQUE NOT NULL,
    monto_apertura DECIMAL(12,2) NOT NULL
);

CREATE TABLE empleados (
    id_empleado SERIAL PRIMARY KEY,
    codigo_empleado VARCHAR(20) UNIQUE NOT NULL,
    nombre_completo VARCHAR(100),
    estado_familiar VARCHAR(15) CHECK (estado_familiar IN ('Soltero','Casado','Divorciado','Viudo')),
    documento_identidad VARCHAR(20) UNIQUE,
    fecha_nacimiento DATE,
    edad INTEGER,
    direccion TEXT ,
    puesto VARCHAR(50),
    departamento VARCHAR(20) CHECK (departamento IN ('Finanzas','Atención al cliente','Gerencia','Servicios varios','Seguridad')),
    sueldo DECIMAL(10,2),
    profesion VARCHAR(50),
    correo VARCHAR(100),
    telefono VARCHAR(15)
);

CREATE TABLE notificaciones (
    id_notificacion SERIAL PRIMARY KEY,
    id_empleado INTEGER NOT NULL,
    mensaje TEXT NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    leida BOOLEAN DEFAULT FALSE
);

CREATE TABLE prestamos (
    id_prestamo SERIAL PRIMARY KEY,
    id_producto INTEGER UNIQUE NOT NULL,
    numero_referencia VARCHAR(20) UNIQUE NOT NULL,
    monto_otorgado DECIMAL(12,2) NOT NULL,
    plazo_pago INTEGER NOT NULL,
    cuota DECIMAL(12,2) NOT NULL,
    tasa_interes DECIMAL(5,2) NOT NULL,
    cuota_seguro DECIMAL(10,2) NOT NULL
);

CREATE TABLE productos_financieros (
    id_producto SERIAL PRIMARY KEY,
    id_cliente INTEGER NOT NULL,
    tipo_producto VARCHAR(10) CHECK (tipo_producto IN ('Cuenta','Tarjeta','Prestamo','Seguro')) NOT NULL,
    detalle_producto TEXT NOT NULL,
    fecha_adquisicion DATE NOT NULL,
    fecha_cierre DATE
);

CREATE TABLE seguros (
    id_seguro SERIAL PRIMARY KEY,
    id_producto INTEGER UNIQUE NOT NULL,
    categoria VARCHAR(10) CHECK (categoria IN ('Vida','Salud','Asistencia')) NOT NULL,
    monto_asegurado DECIMAL(12,2) NOT NULL,
    plazo_pago VARCHAR(10) CHECK (plazo_pago IN ('Mensual','Trimestral','Semestral','Anual')) NOT NULL,
    cuota DECIMAL(10,2) NOT NULL,
    renta_hospitalizacion DECIMAL(10,2),
    causas TEXT
);

CREATE TABLE solicitudes_productos (
    id_solicitud SERIAL PRIMARY KEY,
    id_cliente INTEGER NOT NULL,
    tipo_producto VARCHAR(10) CHECK (tipo_producto IN ('Cuenta','Tarjeta','Prestamo','Seguro')) NOT NULL,
    detalle_solicitud TEXT NOT NULL,
    estado VARCHAR(10) DEFAULT 'Pendiente' CHECK (estado IN ('Pendiente','Aprobada','Rechazada')),
    fecha_solicitud TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    fecha_respuesta TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    id_empleado_respuesta INTEGER
);

CREATE TABLE tarjetas (
    id_tarjeta SERIAL PRIMARY KEY,
    id_producto INTEGER UNIQUE NOT NULL,
    numero_tarjeta VARCHAR(20) UNIQUE NOT NULL,
    limite_monto DECIMAL(12,2) NOT NULL,
    tipo_red VARCHAR(10) CHECK (tipo_red IN ('Visa','MasterCard')) NOT NULL,
    categoria VARCHAR(15) CHECK (categoria IN ('Clasica','Infinite','Oro','Platinum','Empresarial')) NOT NULL,
    tasa_interes DECIMAL(5,2) NOT NULL,
    costo_membresia DECIMAL(10,2) NOT NULL
);

CREATE TABLE transacciones (
    id_transaccion SERIAL PRIMARY KEY,
    id_producto INTEGER NOT NULL,
    id_empleado INTEGER NOT NULL,
    monto DECIMAL(12,2) NOT NULL,
    fecha_transaccion TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL
);

CREATE TABLE usuarios (
    id_usuario SERIAL PRIMARY KEY,
    id_empleado INTEGER UNIQUE NOT NULL,
    usuario VARCHAR(50) UNIQUE NOT NULL,
    contrasena VARCHAR(255) NOT NULL,
    rol VARCHAR(10) CHECK (rol IN ('admin','cajero','gerente'))
);

ALTER TABLE cuentas
    ADD CONSTRAINT fk_id_producto_cuentas FOREIGN KEY (id_producto) REFERENCES productos_financieros(id_producto);

ALTER TABLE notificaciones
    ADD CONSTRAINT fk_id_empleado_notificaciones FOREIGN KEY (id_empleado) REFERENCES empleados(id_empleado);

ALTER TABLE prestamos
    ADD CONSTRAINT fk_id_producto_prestamos FOREIGN KEY (id_producto) REFERENCES productos_financieros(id_producto);

ALTER TABLE productos_financieros
    ADD CONSTRAINT fk_id_cliente_productos FOREIGN KEY (id_cliente) REFERENCES clientes(id_cliente);

ALTER TABLE seguros
    ADD CONSTRAINT fk_id_producto_seguros FOREIGN KEY (id_producto) REFERENCES productos_financieros(id_producto);

ALTER TABLE solicitudes_productos
    ADD CONSTRAINT fk_id_cliente_solicitudes FOREIGN KEY (id_cliente) REFERENCES clientes(id_cliente),
    ADD CONSTRAINT fk_id_empleado_respuesta_solicitudes FOREIGN KEY (id_empleado_respuesta) REFERENCES empleados(id_empleado);

ALTER TABLE tarjetas
    ADD CONSTRAINT fk_id_producto_tarjetas FOREIGN KEY (id_producto) REFERENCES productos_financieros(id_producto);

ALTER TABLE transacciones
    ADD CONSTRAINT fk_id_producto_transacciones FOREIGN KEY (id_producto) REFERENCES productos_financieros(id_producto),
    ADD CONSTRAINT fk_id_empleado_transacciones FOREIGN KEY (id_empleado) REFERENCES empleados(id_empleado);

ALTER TABLE usuarios
    ADD CONSTRAINT fk_id_empleado_usuarios FOREIGN KEY (id_empleado) REFERENCES empleados(id_empleado);