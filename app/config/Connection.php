<?php
// app/config/Connection.php

class Connection {
    private static $instance = null;
    private $pdo;
    
    private function __construct() {
        $cfg = require __DIR__ . '/database.php';
        $dsn = "mysql:host={$cfg['host']};dbname={$cfg['dbname']};charset={$cfg['charset']}";
        
        try {
            $this->pdo = new PDO($dsn, $cfg['user'], $cfg['pass'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false, // Seguridad adicional
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
            ]);
        } catch (PDOException $e) {
            // Log del error (en producción no mostrar detalles)
            $this->logError($e->getMessage());
            
            // Mensaje genérico para el usuario
            if (getenv('APP_ENV') === 'production') {
                die('Error de conexión a la base de datos. Contacte al administrador.');
            } else {
                die('DB Connection failed: ' . $e->getMessage());
            }
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Connection();
        }
        return self::$instance->pdo;
    }
    
    // Prevenir clonación
    private function __clone() {}
    
    // Prevenir deserialización
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
    
    /**
     * Log de errores (guardar en archivo)
     */
    private function logError($message) {
        $logFile = __DIR__ . '/../../logs/db_errors.log';
        $logDir = dirname($logFile);
        
        // Crear directorio logs si no existe
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[{$timestamp}] DB Error: {$message}\n";
        
        error_log($logMessage, 3, $logFile);
    }
}
