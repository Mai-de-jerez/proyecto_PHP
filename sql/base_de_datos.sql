-- =====================================================================
-- SONIDO INTERIOR - Schema de Base de Datos
-- Motor: MySQL 8.0+
-- Descripcion: E-commerce de cuencos tibetanos / productos de sonido
-- =====================================================================

DROP DATABASE IF EXISTS sonido_interior;
CREATE DATABASE sonido_interior
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE sonido_interior;

-- =====================================================================
-- TABLA 1: usuarios
-- =====================================================================
CREATE TABLE usuarios (
    id_usuario      INT AUTO_INCREMENT PRIMARY KEY,
    nombre          VARCHAR(100) NOT NULL,
    email           VARCHAR(150) NOT NULL,
    usuario         VARCHAR(100) NOT NULL,
    password        VARCHAR(255) NOT NULL,
    rol             VARCHAR(50)  NOT NULL DEFAULT 'CLIENTE',
    fecha_registro  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT uq_usuarios_email   UNIQUE (email),
    CONSTRAINT uq_usuarios_usuario UNIQUE (usuario),
    CONSTRAINT chk_usuarios_rol CHECK (rol IN ('CLIENTE', 'ADMIN'))
) ENGINE=InnoDB;

-- =====================================================================
-- TABLA 2: categorias
-- =====================================================================
CREATE TABLE categorias (
    id_categoria    INT AUTO_INCREMENT PRIMARY KEY,
    nombre          VARCHAR(100) NOT NULL,
    descripcion     TEXT,
    activo          BOOLEAN NOT NULL DEFAULT TRUE
) ENGINE=InnoDB;

-- =====================================================================
-- TABLA 3: productos
-- =====================================================================
CREATE TABLE productos (
    id_producto     INT AUTO_INCREMENT PRIMARY KEY,
    nombre          VARCHAR(150)  NOT NULL,
    descripcion     TEXT,
    precio          DECIMAL(10,2) NOT NULL,
    stock           INT           NOT NULL DEFAULT 0,
    imagen          VARCHAR(255),
    diametro        DECIMAL(5,2),
    peso            DECIMAL(8,2),
    material        VARCHAR(100),
    nota_musical    VARCHAR(50),
    procedencia     VARCHAR(100),
    activo          BOOLEAN       NOT NULL DEFAULT TRUE,
    fecha_alta      DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    id_categoria    INT           NOT NULL,

    CONSTRAINT fk_productos_categoria
        FOREIGN KEY (id_categoria) REFERENCES categorias(id_categoria)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT chk_productos_precio CHECK (precio >= 0),
    CONSTRAINT chk_productos_stock  CHECK (stock >= 0)
) ENGINE=InnoDB;

-- =====================================================================
-- TABLA 4: mensajes  (formulario de contacto, sin FK)
-- =====================================================================
CREATE TABLE mensajes (
    id_mensaje      INT AUTO_INCREMENT PRIMARY KEY,
    nombre          VARCHAR(100) NOT NULL,
    email           VARCHAR(150) NOT NULL,
    telefono        VARCHAR(30),
    motivo          VARCHAR(100),
    mensaje         TEXT         NOT NULL,
    fecha_envio     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    leido           BOOLEAN      NOT NULL DEFAULT FALSE
) ENGINE=InnoDB;

-- =====================================================================
-- TABLA 5: pedidos
-- =====================================================================
CREATE TABLE pedidos (
    id_pedido        INT AUTO_INCREMENT PRIMARY KEY,
    fecha_pedido     DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    estado           VARCHAR(50)   NOT NULL DEFAULT 'PENDIENTE',
    total            DECIMAL(10,2) NOT NULL DEFAULT 0,
    direccion_envio  VARCHAR(255)  NOT NULL,
    id_usuario       INT           NOT NULL,

    CONSTRAINT fk_pedidos_usuario
        FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT chk_pedidos_estado CHECK (
        estado IN ('PENDIENTE', 'PAGADO', 'ENVIADO', 'ENTREGADO', 'CANCELADO')
    ),
    CONSTRAINT chk_pedidos_total CHECK (total >= 0)
) ENGINE=InnoDB;

-- =====================================================================
-- TABLA 6: detalle_pedido
-- =====================================================================
CREATE TABLE detalle_pedido (
    id_detalle      INT AUTO_INCREMENT PRIMARY KEY,
    cantidad        INT           NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    subtotal        DECIMAL(10,2) NOT NULL,
    id_pedido       INT           NOT NULL,
    id_producto     INT           NOT NULL,

    CONSTRAINT fk_detalle_pedido
        FOREIGN KEY (id_pedido) REFERENCES pedidos(id_pedido)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_detalle_producto
        FOREIGN KEY (id_producto) REFERENCES productos(id_producto)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT chk_detalle_cantidad CHECK (cantidad > 0),
    CONSTRAINT chk_detalle_precio   CHECK (precio_unitario >= 0)
) ENGINE=InnoDB;

-- =====================================================================
-- TABLA 7: carrito  (relacion 1:1 con usuarios)
-- =====================================================================
CREATE TABLE carrito (
    id_carrito      INT AUTO_INCREMENT PRIMARY KEY,
    fecha_creacion  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    id_usuario      INT NOT NULL,

    CONSTRAINT fk_carrito_usuario
        FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT uq_carrito_usuario UNIQUE (id_usuario)  
) ENGINE=InnoDB;

-- =====================================================================
-- TABLA 8: carrito_producto
-- =====================================================================
CREATE TABLE carrito_producto (
    id_carrito_producto INT AUTO_INCREMENT PRIMARY KEY,
    cantidad            INT           NOT NULL DEFAULT 1,
    precio_unitario     DECIMAL(10,2) NOT NULL,
    id_carrito          INT           NOT NULL,
    id_producto         INT           NOT NULL,

    CONSTRAINT fk_carritoprod_carrito
        FOREIGN KEY (id_carrito) REFERENCES carrito(id_carrito)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_carritoprod_producto
        FOREIGN KEY (id_producto) REFERENCES productos(id_producto)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT uq_carrito_producto UNIQUE (id_carrito, id_producto), 
    CONSTRAINT chk_carritoprod_cantidad CHECK (cantidad > 0)
) ENGINE=InnoDB;

-- =====================================================================
-- FIN DEL SCRIPT
-- =====================================================================