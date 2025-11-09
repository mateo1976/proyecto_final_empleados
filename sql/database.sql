-- Script SQL para crear la base y tablas
CREATE DATABASE IF NOT EXISTS proyecto_final_empleados CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE proyecto_final_empleados;

CREATE TABLE IF NOT EXISTS administrador (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS empleado (
  id INT AUTO_INCREMENT PRIMARY KEY,
  documento VARCHAR(50) NOT NULL UNIQUE,
  nombre VARCHAR(150) NOT NULL,
  sexo ENUM('M','F') NOT NULL,
  domicilio VARCHAR(255),
  telefono VARCHAR(50),
  correo VARCHAR(150),
  fechaIngreso DATE,
  fechaNacimiento DATE,
  sueldoBasico DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
