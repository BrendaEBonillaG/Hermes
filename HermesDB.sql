CREATE DATABASE Hermes;
USE Hermes;
SHOW TABLES;
DROP DATABASE Hermes;

-- Tabla de Usuarios
CREATE TABLE Usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    correo VARCHAR(100) NOT NULL,
    nombreUsu VARCHAR(50) NOT NULL,
    contrasena VARCHAR(255) NOT NULL,
    rol VARCHAR(50) NOT NULL,
	foto LONGBLOB,
    fotoNombre VARCHAR(100),
    nombres VARCHAR(100) NOT NULL,
    apePa VARCHAR(100) NOT NULL,
    apeMa VARCHAR(100) NOT NULL,
    fechaNacim DATE,
    sexo VARCHAR(30) NOT NULL,
    fechaIngreso TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    privacidad VARCHAR(20) NOT NULL,
    estado TINYINT(1) NOT NULL DEFAULT 1
);
SELECT * FROM Usuarios;
SELECT id, nombre FROM Listas WHERE id_usuario = 6;

INSERT INTO Usuarios (correo, nombreUsu,contrasena,rol,fotoNombre,nombres,apePa,apeMa,fechaNacim,sexo,privacidad) 
VALUES ('zaptos@gmail.com', 'Admin05', '$2y$10$sCoyXA5h3bHWEsaQnyXuTOQdXBwj9ixELF02Tq7ndOMH7zE1s1ul2','administrador','foto','alvaro','saldivar','garza','2000-10-05','masculino','publico');

SELECT * FROM Productos;
SELECT * FROM Imagenes_Productos;
SELECT * FROM Videos_Productos;

-- TABLAS PARA MANEJO DE PRODUCTOS ----------------
-- Tabla de Categorías
CREATE TABLE Categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) UNIQUE NOT NULL,
    descripcion TEXT,
    id_usuario INT,
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id) 
);

-- Tabla de Productos
CREATE TABLE Productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT NOT NULL,
    precio DECIMAL(10 , 2 ) NOT NULL,
    cantidad_Disponible INT NOT NULL,
    tipo VARCHAR(50) NOT NULL,
    id_vendedor INT NOT NULL,
    id_categoria INT NOT NULL,
    estado VARCHAR(50) DEFAULT 'pendiente' NOT NULL,
    FOREIGN KEY (id_vendedor)
        REFERENCES Usuarios (id),
    FOREIGN KEY (id_categoria)
        REFERENCES Categorias (id)
);
DROP TABLE Compra;


CREATE TABLE Compra (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_comprador INT NOT NULL,
    id_producto INT NOT NULL,
    cantidad INT NOT NULL,
    estado VARCHAR(50) DEFAULT 'espera' NOT NULL,
    
    fechaIngreso TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_comprador)
        REFERENCES Usuarios (id),
    FOREIGN KEY (id_producto)
        REFERENCES productos (id)
);
SELECT*FROM Compra;
SELECT*FROM productos;
SELECT*FROM Carritos;
DELIMITER $$

CREATE TRIGGER after_insert_compra
    AFTER INSERT
    ON Compra
    FOR EACH ROW
BEGIN
    -- Reducción de la cantidad en la tabla Productos
    UPDATE Productos
    SET cantidad_Disponible = cantidad_Disponible - NEW.cantidad
    WHERE id = NEW.id_producto;
END $$

DELIMITER ;

-- Tabla de Imágenes de Productos
CREATE TABLE Imagenes_Productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_producto INT NOT NULL,
    url_imagen VARCHAR(255) NOT NULL,
    FOREIGN KEY (id_producto) REFERENCES Productos(id) 
);

-- Tabla de Videos de Productos
CREATE TABLE Videos_Productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_producto INT NOT NULL,
    url_video VARCHAR(255) NOT NULL,
    FOREIGN KEY (id_producto) REFERENCES Productos(id) 
);

-- TABLAS PARA MANEJO DE WISHLIST ---------------
DROP TABLE Listas;


SELECT * FROM Listas;
-- Tabla de Listas de Compras
CREATE TABLE Listas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    privacidad VARCHAR(30) NOT NULL,
    foto VARCHAR(255), -- Campo para la foto
    id_usuario INT NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id) 
);

-- Tabla de Productos en Listas
CREATE TABLE Listas_Productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_lista INT NOT NULL,
    id_producto INT NOT NULL,
    FOREIGN KEY (id_lista) REFERENCES Listas(id) ,
    FOREIGN KEY (id_producto) REFERENCES Productos(id) 
);

INSERT INTO Listas (nombre, descripcion, privacidad, foto, id_usuario)
VALUES ('Favoritos de verano', 'Productos que me gustaron para el verano', 'publico', 'uploads/imagenes/6828481f0ebbe_1728272896855.jpg', 6);


-- TABLAS PARA MANEJO DE CARRITO DE COMPRA ---------------
-- Tabla de Carrito de Compras
CREATE TABLE Carritos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    fecha_creado TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estado ENUM('activo', 'comprado', 'cancelado') DEFAULT 'activo',
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id)
);

CREATE TABLE CarritoDetalles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_carrito INT NOT NULL,
    id_producto INT NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) GENERATED ALWAYS AS (cantidad * precio_unitario) STORED,
    nota TEXT,
    FOREIGN KEY (id_carrito) REFERENCES Carritos(id),
    FOREIGN KEY (id_producto) REFERENCES Productos(id)
);
-- TABLAS PARA MANEJO DE REPORTES VENDIDOS Y PEDIDOS --------------
-- Tabla de Pedidos
CREATE TABLE Pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_comprador INT NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estado ENUM('pendiente', 'procesando', 'enviado', 'entregado', 'cancelado') DEFAULT 'pendiente',
    FOREIGN KEY (id_comprador) REFERENCES Usuarios(id)
);


-- Tabla de Detalles de Pedido
CREATE TABLE Detalles_Pedido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT NOT NULL,
    id_producto INT NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_pedido) REFERENCES Pedidos(id),
    FOREIGN KEY (id_producto) REFERENCES Productos(id)
);


-- Tabla de Valoraciones y Comentarios
CREATE TABLE Valoraciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_producto INT NOT NULL,
    id_usuario INT NOT NULL,
    puntuacion INT CHECK (puntuacion BETWEEN 1 AND 10),
    comentario TEXT,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_producto) REFERENCES Productos(id),
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id),
    UNIQUE (id_producto, id_usuario)
);

-- Tabla de Cotizaciones
CREATE TABLE Cotizaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_producto INT NOT NULL,
    id_comprador INT NOT NULL,
    precio DECIMAL(10,2) NOT NULL,         -- Precio acordado en la cotización
    cantidad INT NOT NULL,                  -- Cantidad solicitada
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_producto) REFERENCES Productos(id),
    FOREIGN KEY (id_comprador) REFERENCES Usuarios(id)
);

SELECT * FROM Mensajes_Privado;
SHOW CREATE TABLE Chat_Privado;

CREATE TABLE Chat_Privado (
    id_chat INT PRIMARY KEY AUTO_INCREMENT,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    id_remitente INT NOT NULL,
    id_emisor INT NOT NULL,
    CONSTRAINT chk_diferentes CHECK (id_remitente <> id_emisor),
    CONSTRAINT fk_remitente FOREIGN KEY (id_remitente) REFERENCES usuarios(id) ON DELETE CASCADE,
    CONSTRAINT fk_emisor FOREIGN KEY (id_emisor) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE TABLE Mensajes_Privado (
    id_mensaje INT PRIMARY KEY AUTO_INCREMENT,
    id_chat INT NOT NULL,
    id_usuario INT NOT NULL,
    contenido TEXT NOT NULL,
    fecha_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    tipo VARCHAR(500) DEFAULT 'texto',
    visto BIT(1) NOT NULL DEFAULT b'0',
    CONSTRAINT fk_mensaje_chat FOREIGN KEY (id_chat) REFERENCES chat_privado(id_chat) ON DELETE CASCADE,
    CONSTRAINT fk_mensaje_usuario FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE
);

TRUNCATE TABLE Usuarios;

DROP TABLE IF EXISTS Usuarios;
SELECT * FROM Usuarios;
SELECT * FROM Productos;
SELECT * FROM Categorias;


SHOW PROCEDURE STATUS WHERE Db = 'Hermes';
DROP PROCEDURE IF EXISTS sp_insert_usuario;

DELIMITER $$

CREATE PROCEDURE sp_insert_usuario(
    IN p_correo VARCHAR(100),
    IN p_nombreUsu VARCHAR(50),
    IN p_contrasena VARCHAR(255),
    IN p_rol VARCHAR(50),
    IN p_foto LONGBLOB,
    IN p_fotoNombre VARCHAR(100),
    IN p_nombres VARCHAR(100),
    IN p_apePa VARCHAR(100),
    IN p_apeMa VARCHAR(100),
    IN p_fechaNacim DATE,
    IN p_sexo VARCHAR(30),
    IN p_privacidad VARCHAR(20)
)
BEGIN
    -- Insertar los datos en la tabla Usuarios
    INSERT INTO Usuarios (
        correo, nombreUsu, contrasena, rol, foto, fotoNombre, nombres, apePa, apeMa, fechaNacim, sexo, privacidad
    ) VALUES (
        p_correo, p_nombreUsu, p_contrasena, p_rol, p_foto, p_fotoNombre, p_nombres, p_apePa, p_apeMa, p_fechaNacim, p_sexo, p_privacidad
    );
END $$

DELIMITER ;

INSERT INTO Categorias (nombre, descripcion, id_usuario) 
VALUES ('zaptos', 'Descripción', 1);




INSERT INTO Productos (nombre, descripcion, precio,cantidad_Disponible, tipo,id_vendedor,id_categoria,estado) 
VALUES ('Producto 3', 'Descripción del producto 1', 249.99,4, 'jabon',10,1, 'pendiente');
INSERT INTO Productos (nombre, descripcion, precio,cantidad_Disponible, tipo,id_vendedor,id_categoria,estado) 
VALUES ('Producto 6', 'Descripción del producto 4', 29.99,4, 'jamon',1,1, 'pendiente');
INSERT INTO Productos (nombre, descripcion, precio,cantidad_Disponible, tipo,id_vendedor,id_categoria,estado) 
VALUES ('Producto 2', 'Descripción del producto 2', 24.99,4, 'weq',1,1, 'activo');
INSERT INTO Productos (nombre, descripcion, precio,cantidad_Disponible, tipo,id_vendedor,id_categoria,estado) 
VALUES ('Producto 3', 'Descripción del producto 4', 49.99,4, 'qwewq',1,1, 'activo');

INSERT INTO Productos (nombre, descripcion, precio,cantidad_Disponible, tipo,id_vendedor,id_categoria,estado) 
VALUES ('Producto 2', 'Descripción del producto 2', 29.99,4, 'jabon',2,1, 'activo');


INSERT INTO Listas (nombre, descripcion, privacidad,id_usuario) 
VALUES ('lista 1', 'Descripción del producto 2', 'publica',3);
