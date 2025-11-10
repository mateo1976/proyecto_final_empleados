-- Agregar tabla para historial de aumentos
USE proyecto_final_empleados;

CREATE TABLE IF NOT EXISTS historial_aumentos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  empleado_id INT NOT NULL,
  sueldo_anterior DECIMAL(12,2) NOT NULL,
  sueldo_nuevo DECIMAL(12,2) NOT NULL,
  porcentaje DECIMAL(5,2) NOT NULL,
  tipo_aumento VARCHAR(20) NOT NULL COMMENT 'global, selectivo, por_rango',
  admin_id INT NOT NULL,
  fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (empleado_id) REFERENCES empleado(id) ON DELETE CASCADE,
  FOREIGN KEY (admin_id) REFERENCES administrador(id) ON DELETE CASCADE,
  INDEX idx_empleado (empleado_id),
  INDEX idx_fecha (fecha)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;