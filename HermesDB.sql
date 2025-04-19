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
    privacidad VARCHAR(20) NOT NULL
);
DROP TABLE IF EXISTS Usuarios;
SELECT * FROM Usuarios;


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
    precio DECIMAL(10,2) NOT NULL,
    cantidad_Disponible INT NOT NULL,
    tipo VARCHAR (50) NOT NULL,
    id_vendedor INT NOT NULL,
    id_categoria INT NOT NULL,
    estado VARCHAR(50) DEFAULT 'pendiente' NOT NULL,
    FOREIGN KEY (id_vendedor) REFERENCES Usuarios(id) ,
    FOREIGN KEY (id_categoria) REFERENCES Categorias(id) 
);

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

-- Tabla de Listas de Compras
CREATE TABLE Listas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    privacidad VARCHAR(30) NOT NULL,
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

-- Tabla de Carrito de Compras
CREATE TABLE Carrito (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_producto INT NOT NULL,
    cantidad INT NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id) ,
    FOREIGN KEY (id_producto) REFERENCES Productos(id) 
);

-- Tabla de Pedidos
CREATE TABLE Pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_comprador INT NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_comprador) REFERENCES Usuarios(id) 
);

-- Tabla de Detalles de Pedido
CREATE TABLE Detalles_Pedido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT NOT NULL,
    id_producto INT NOT NULL,
    cantidad INT NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_pedido) REFERENCES Pedidos(id) ,
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
    FOREIGN KEY (id_producto) REFERENCES Productos(id) ,
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id) 
);

-- Tabla de Cotizaciones
CREATE TABLE Cotizaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_producto INT NOT NULL,
    id_comprador INT NOT NULL,
    mensaje TEXT NOT NULL,
    estado VARCHAR(30) DEFAULT 'pendiente',
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_producto) REFERENCES Productos(id) ,
    FOREIGN KEY (id_comprador) REFERENCES Usuarios(id) 
);

-- Tabla de Métodos de Pago
CREATE TABLE Metodos_Pago (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    tipo VARCHAR(30) NOT NULL,
    detalles VARCHAR(255) NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id) 
);

-- Tabla de Transacciones
CREATE TABLE Transacciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT NOT NULL,
    id_metodo_pago INT NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    monto DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_pedido) REFERENCES Pedidos(id) ,
    FOREIGN KEY (id_metodo_pago) REFERENCES Metodos_Pago(id) 
);

-- Tabla de Mensajes entre Usuarios
CREATE TABLE Mensajes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_emisor INT NOT NULL,
    id_receptor INT NOT NULL,
    mensaje TEXT NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_emisor) REFERENCES Usuarios(id) ,
    FOREIGN KEY (id_receptor) REFERENCES Usuarios(id) 
);

SELECT * FROM Mensajes;

DELIMITER //

CREATE PROCEDURE sp_registrar_producto(
    IN p_nombre VARCHAR(100),
    IN p_descripcion TEXT,
    IN p_precio DECIMAL(10,2),
    IN p_cantidad INT,
    IN p_categoria VARCHAR(100),
    IN p_id_vendedor INT,
    IN p_acepta_cotizaciones BOOLEAN,
    OUT p_id_producto INT,
    OUT p_mensaje VARCHAR(255)
)
BEGIN
    DECLARE v_id_categoria INT;
    DECLARE v_categoria_existe INT;
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        GET DIAGNOSTICS CONDITION 1
        @sqlstate = RETURNED_SQLSTATE, 
        @errno = MYSQL_ERRNO, 
        @text = MESSAGE_TEXT;
        
        SET p_mensaje = CONCAT('Error: ', @sqlstate, ' - ', @errno, ' - ', @text);
    END;
    
    START TRANSACTION;
    
    -- Verificar si la categoría existe
    SELECT COUNT(*) INTO v_categoria_existe FROM Categorias WHERE nombre = p_categoria;
    
    -- Si no existe, crear nueva categoría
    IF v_categoria_existe = 0 THEN
        INSERT INTO Categorias (nombre, id_usuario) VALUES (p_categoria, p_id_vendedor);
        SET v_id_categoria = LAST_INSERT_ID();
    ELSE
        -- Obtener ID de categoría existente
        SELECT id INTO v_id_categoria FROM Categorias WHERE nombre = p_categoria LIMIT 1;
    END IF;
    
    -- Insertar el producto
    INSERT INTO Productos (
        nombre, 
        descripcion, 
        precio, 
        cantidad_Disponible, 
        tipo, 
        id_vendedor, 
        id_categoria, 
        estado, 
        acepta_cotizaciones
    ) VALUES (
        p_nombre,
        p_descripcion,
        p_precio,
        p_cantidad,
        'físico',
        p_id_vendedor,
        v_id_categoria,
        'pendiente',
        p_acepta_cotizaciones
    );
    
    SET p_id_producto = LAST_INSERT_ID();
    SET p_mensaje = 'Producto registrado correctamente';
    
    COMMIT;
END //

DELIMITER ;