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
CREATE TABLE info_aparato(
    id INT PRIMARY KEY AUTO_INCREMENT,    
    id_categoria INT,
    estado CHAR,
    descripcion VARCHAR(100),
    FOREIGN KEY (id_categoria)
    REFERENCES aparato(id)
);


create table proveedor(
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(20)
    
);

create table paquete(
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(12),
    descripcion VARCHAR(50),
    precio VARCHAR(10),
    duracion INT,
    activo TINYINT
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
    genero CHAR NOT NULL,
    ultimo_pago INT
);
CREATE TABLE fecha(
    id INT PRIMARY KEY AUTO_INCREMENT,    
    fecha DATE
);

create table asistencia(
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_fecha INT,
    FOREIGN KEY (id_fecha)
    REFERENCES fecha(id),
    id_cliente INT,
    FOREIGN KEY (id_cliente)
    REFERENCES cliente(id_cliente)
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
    fecha_vencimiento DATE,
    id_cliente INT,
    id_paquete INT,
    monto VARCHAR(10),
    modo VARCHAR(25),
    
    FOREIGN KEY (id_cliente)
    REFERENCES cliente(id_cliente),
    FOREIGN KEY (id_paquete)
    REFERENCES paquete(id)
);

CREATE TABLE admin_aparato(
    id INT PRIMARY KEY AUTO_INCREMENT,  
    id_aparato INT,
    FOREIGN KEY (id_aparato)
    REFERENCES info_aparato(id),
    id_admin INT,
    FOREIGN KEY (id_admin)
    REFERENCES empleado(id_empleado),
    fecha DATE NOT NULL,
    accion CHAR NOT NULL
);


CREATE TABLE producto(
    id_producto INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(60) NOT NULL,
    descripcion VARCHAR(90) NOT NULL,
    precio_entrada FLOAT NOT NULL,
    precio_salida FLOAT NOT NULL,
    disponibles INT NOT NULL,
    id_proveedor INT NOT NULL,
    activo BOOLEAN,
    FOREIGN KEY (id_proveedor)
    REFERENCES proveedor(id)
);

CREATE TABLE empleado_producto(
    id INT PRIMARY KEY AUTO_INCREMENT,    
    id_empleado INT,
    FOREIGN KEY (id_empleado)
    REFERENCES empleado(id_empleado),
    id_producto INT,
    cantidad INT noT NULL,
    fecha DATE NOT NULL,
    total FLOAT NOT NULL,

    FOREIGN KEY (id_producto)
    REFERENCES producto(id_producto)
);




