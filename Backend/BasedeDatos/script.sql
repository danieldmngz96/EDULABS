SELECT * FROM edulabs.usuarios;

INSERT INTO usuarios (nombre, email, password, rol_id, grupo_id, cuota_mb, created_at, updated_at)
VALUES ('Administrador', 'admin@demo.com', '$2y$10$JH7kLk0eNw3L0R1sVb7kQO.g6QbP1ZKf1ZbL0aW1c1kUe6Eo9n8Ca', 1, NULL, 50, NOW(), NOW());

ALTER TABLE usuarios
ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE usuarios
MODIFY COLUMN password VARCHAR(255) NOT NULL;

UPDATE usuarios
SET password = '$2y$12$/ftbuzyTns3bHRIj9XWKW.IrpstdOa9.p9nE3k6bDghNMOG/V.Q6y'
WHERE email = 'admin@demo.com';

DESCRIBE edulabs.usuarios;

INSERT INTO roles (id, nombre)
VALUES  
(1, 'Administrador'),  
(2, 'Profesor'),  
(3, 'Estudiante')  
ON DUPLICATE KEY UPDATE nombre = VALUES(nombre);


DESCRIBE grupos;
SELECT * FROM edulabs.grupos;

INSERT INTO grupos (id, nombre, cuota_mb)
VALUES  
(1, 'Grupo General', 50),  
(2, 'Matemáticas', 20),  
(3, 'Ciencias', 30)  
ON DUPLICATE KEY UPDATE 
  nombre = VALUES(nombre),
  cuota_mb = VALUES(cuota_mb);

SELECT id, nombre, grupo_id FROM usuarios WHERE email = 'admin@demo.com';

UPDATE usuarios
SET grupo_id = 1
WHERE email = 'admin@demo.com';


DESCRIBE edulabs.archivos;
select * FROM edulabs.archivos;
INSERT INTO `archivos` 
(`usuario_id`, `nombre_original`, `nombre_guardado`, `extension`, `tamaño_kb`, `ruta`) 
VALUES 
(1, 'documentos_prueba', 'documents_prueba', '.rar', 800, 'C:\\Users\\danar');

ALTER TABLE archivos
ADD COLUMN created_at TIMESTAMP NULL,
ADD COLUMN updated_at TIMESTAMP NULL;


ALTER TABLE archivos MODIFY usuario_id BIGINT NOT NULL DEFAULT 1;
ALTER TABLE archivos 
ALTER COLUMN usuario_id SET DEFAULT 1;

