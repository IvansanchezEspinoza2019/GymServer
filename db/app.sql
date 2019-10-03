create table access(
    id INT PRIMARY KEY AUTO_INCREMENT,
    user VARCHAR(15),
    password VARCHAR(15),
    tipo char
);

create table cp(
    id INT PRIMARY KEY AUTO_INCREMENT,
    codigo varchar(6)
);

create table colonia(
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(20)
);


create table puesto(
    id INT PRIMARY KEY AUTO_INCREMENT,
    puesto VARCHAR(20),
    sueldo FLOAT
);

create table aparato(
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(20)
);

create table proveedor(
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(20),
    correo VARCHAR(50),
    telefono VARCHAR(13)
);
create table estado(
    id INT PRIMARY KEY AUTO_INCREMENT,
    estado char
);

create table paquete(
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(12),
    descripcion VARCHAR(50),
    precio FLOAT
);

create table fecha(
    id INT PRIMARY KEY AUTO_INCREMENT,
    fecha DATE
);


CREATE TABLE cliente(
    id_cliente INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(90) NOT NULL,
    apellido_p VARCHAR(45) NOT NULL,
    apellido_m VARCHAR(45) NOT NULL,
    foto VARCHAR(130) NOT NULL,
    calle VARCHAR(45) NOT NULL,
    numero_calle VARCHAR(45) NOT NULL,
    telefono VARCHAR(20) NOT NULL,  
    fecha_ingreso DATE,
    id_cp INT,
    FOREIGN KEY (id_cp)
    REFERENCES cp(id),
    id_colonia INT,
    FOREIGN KEY (id_colonia)
    REFERENCES colonia(id),
    id_access INT,
    FOREIGN KEY (id_access)
    REFERENCES access(id),
    fecha_nacimiento DATE NOT NULL, 
    activo BOOLEAN NOT NULL,
    numero_interior VARCHAR(8) NULL,
    genero CHAR NOT NULL
);

create table asistencia(
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_fecha INT,
    FOREIGN KEY (id_fecha)
    REFERENCES fecha(id),
    id_cliente INT,
    FOREIGN KEY (id_cliente)
    REFERENCES cliente(id)
);

CREATE TABLE empleado(
    id_empleado INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(90) NOT NULL,
    apellido_p VARCHAR(45) NOT NULL,
    apellido_m VARCHAR(45) NOT NULL,
    foto VARCHAR(130) NOT NULL,
    calle VARCHAR(45) NOT NULL,
    numero_calle VARCHAR(45) NOT NULL,
    telefono VARCHAR(20) NOT NULL,   
    fecha_ingreso DATE,  
    id_cp INT,
    FOREIGN KEY (id_cp)
    REFERENCES cp(id),
    id_colonia INT,
    FOREIGN KEY (id_colonia)
    REFERENCES colonia(id),
    id_acceso INT,
    FOREIGN KEY (id_acceso)
    REFERENCES access(id),
    id_puesto INT,
    FOREIGN KEY (id_puesto)
    REFERENCES puesto(id),
    fecha_nacimiento DATE NOT NULL, 
    activo BOOLEAN NOT NULL,
    numero_interior VARCHAR(8) NULL,
    genero CHAR NOT NULL
);

CREATE TABLE cliente_paquete(
    id_pago INT PRIMARY KEY AUTO_INCREMENT,  
    fecha_pago DATE,  
    id_cliente INT,
    id_paquete INT,
    monto FLOAT,
    
    FOREIGN KEY (id_cliente)
    REFERENCES cliente(id_cliente),
    FOREIGN KEY (id_paquete)
    REFERENCES paquete(id)
);

CREATE TABLE paquete_aparato(
    id_paquete_a INT PRIMARY KEY AUTO_INCREMENT,    
    id_categoria_aparato INT,
    FOREIGN KEY (id_categoria_aparato)
    REFERENCES aparato(id),
    id_paquete INT,
    FOREIGN KEY (id_paquete)
    REFERENCES paquete(id)
);

CREATE TABLE producto(
id INT PRIMARY KEY AUTO_INCREMENT,    
    id_empleado INT,
    FOREIGN KEY (id_empleado)
    REFERENCES empleado(id_empleado),
    id_producto INT,
    FOREIGN KEY (id_producto)
    REFERENCES producto(id)
);

CREATE TABLE empleado_producto(
    id_producto INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(60) NOT NULL,
    descripcion VARCHAR(90) NOT NULL,
    precio_entrada VARCHAR(25) NOT NULL,
    precio_salida VARCHAR(25) NOT NULL,
    disponibles INT NOT NULL,
    id_proveedor INT,
    FOREIGN KEY (id_proveedor)
    REFERENCES proveedor(id)
);

CREATE TABLE informacion(
    id_producto INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(60) NOT NULL,
    id_categoria_ap INT,
    FOREIGN KEY (id_categoria_ap)
    REFERENCES aparato(id),
    id_estado INT,
    FOREIGN KEY (id_estado)
    REFERENCES estado(id)
);


