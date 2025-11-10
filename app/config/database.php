<?php
// app/config/database.php
// Configuración de base de datos con soporte para variables de entorno

// Detectar entorno (desarrollo por defecto)
$env = getenv('APP_ENV') ?: 'development';

// Configuraciones por entorno
$configs = [
    'development' => [
        'host' => getenv('DB_HOST') ?: '127.0.0.1',
        'dbname' => getenv('DB_NAME') ?: 'proyecto_final_empleados',
        'user' => getenv('DB_USER') ?: 'root',
        'pass' => getenv('DB_PASS') ?: '',
        'charset' => 'utf8mb4'
    ],
    'production' => [
        'host' => getenv('DB_HOST') ?: 'localhost',
        'dbname' => getenv('DB_NAME') ?: 'proyecto_final_empleados',
        'user' => getenv('DB_USER'),
        'pass' => getenv('DB_PASS'),
        'charset' => 'utf8mb4'
    ]
];

// Retornar configuración según entorno
return $configs[$env] ?? $configs['development'];
