use edulabs;

CREATE TABLE roles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL
);

INSERT INTO roles (nombre) VALUES ('admin'), ('user');

CREATE TABLE grupos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  cuota_mb INT DEFAULT 10 -- Límite específico por grupo
);

CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  email VARCHAR(150) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  rol_id INT NOT NULL,
  grupo_id INT NULL,
  cuota_mb INT NULL, -- Límite específico por usuario
  FOREIGN KEY (rol_id) REFERENCES roles(id),
  FOREIGN KEY (grupo_id) REFERENCES grupos(id)
);

CREATE TABLE archivos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT NOT NULL,
  nombre_original VARCHAR(255) NOT NULL,
  nombre_guardado VARCHAR(255) NOT NULL,
  extension VARCHAR(10),
  tamaño_kb INT,
  ruta VARCHAR(255),
  creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

CREATE TABLE configuraciones (
  id INT AUTO_INCREMENT PRIMARY KEY,
  clave VARCHAR(100) NOT NULL,
  valor VARCHAR(255) NOT NULL
);

-- Configuraciones iniciales
INSERT INTO configuraciones (clave, valor) VALUES
('cuota_global_mb', '10'),
('extensiones_prohibidas', 'exe,bat,js,php,sh');




