<?php
// app/models/Empleado.php
require_once __DIR__ . '/../config/Connection.php';

class Empleado {
    private $db;
    
    public function __construct() {
        $this->db = Connection::getInstance();
    }

    /**
     * Obtener todos los empleados
     */
    public function all() {
        try {
            $stmt = $this->db->query("SELECT * FROM empleado ORDER BY nombre ASC");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            $this->logError('all', $e->getMessage());
            return [];
        }
    }

    /**
     * Buscar empleado por ID
     */
    public function find($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM empleado WHERE id = :id");
            $stmt->execute(['id' => $id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            $this->logError('find', $e->getMessage());
            return false;
        }
    }

    /**
     * Crear nuevo empleado
     */
    public function create($data) {
        try {
            // Validar datos
            $validated = $this->validateData($data);
            if (!$validated['success']) {
                return ['success' => false, 'errors' => $validated['errors']];
            }
            
            $sql = 'INSERT INTO empleado (documento, nombre, sexo, domicilio, telefono, correo, fechaIngreso, fechaNacimiento, sueldoBasico)
                    VALUES (:documento, :nombre, :sexo, :domicilio, :telefono, :correo, :fechaIngreso, :fechaNacimiento, :sueldoBasico)';
            
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute($validated['data']);
            
            return [
                'success' => $result,
                'id' => $this->db->lastInsertId()
            ];
        } catch (PDOException $e) {
            $this->logError('create', $e->getMessage());
            
            // Detectar error de duplicado
            if ($e->getCode() == 23000) {
                return ['success' => false, 'errors' => ['El documento ya existe en el sistema']];
            }
            
            return ['success' => false, 'errors' => ['Error al crear el empleado']];
        }
    }

    /**
     * Actualizar empleado
     */
    public function update($id, $data) {
        try {
            // Validar datos
            $validated = $this->validateData($data, $id);
            if (!$validated['success']) {
                return ['success' => false, 'errors' => $validated['errors']];
            }
            
            $sql = 'UPDATE empleado SET 
                    documento=:documento, nombre=:nombre, sexo=:sexo, 
                    domicilio=:domicilio, telefono=:telefono, correo=:correo, 
                    fechaIngreso=:fechaIngreso, fechaNacimiento=:fechaNacimiento, 
                    sueldoBasico=:sueldoBasico 
                    WHERE id=:id';
            
            $validated['data']['id'] = $id;
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute($validated['data']);
            
            return ['success' => $result];
        } catch (PDOException $e) {
            $this->logError('update', $e->getMessage());
            
            if ($e->getCode() == 23000) {
                return ['success' => false, 'errors' => ['El documento ya existe en el sistema']];
            }
            
            return ['success' => false, 'errors' => ['Error al actualizar el empleado']];
        }
    }

    /**
     * Eliminar empleado
     */
    public function delete($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM empleado WHERE id = :id");
            $result = $stmt->execute(['id' => $id]);
            return ['success' => $result];
        } catch (PDOException $e) {
            $this->logError('delete', $e->getMessage());
            return ['success' => false, 'errors' => ['Error al eliminar el empleado']];
        }
    }

    /**
     * Validar datos de empleado
     */
    private function validateData($data, $excludeId = null) {
        $errors = [];
        $cleaned = [];
        
        // Documento: requerido, alfanumérico, único
        if (empty($data['documento'])) {
            $errors[] = 'El documento es requerido';
        } else {
            $cleaned['documento'] = trim($data['documento']);
            if (!$this->isDocumentoUnique($cleaned['documento'], $excludeId)) {
                $errors[] = 'El documento ya está registrado';
            }
        }
        
        // Nombre: requerido, mínimo 3 caracteres
        if (empty($data['nombre'])) {
            $errors[] = 'El nombre es requerido';
        } elseif (strlen($data['nombre']) < 3) {
            $errors[] = 'El nombre debe tener al menos 3 caracteres';
        } else {
            $cleaned['nombre'] = trim($data['nombre']);
        }
        
        // Sexo: M o F
        if (empty($data['sexo']) || !in_array($data['sexo'], ['M', 'F'])) {
            $errors[] = 'El sexo debe ser M o F';
        } else {
            $cleaned['sexo'] = $data['sexo'];
        }
        
        // Domicilio: opcional
        $cleaned['domicilio'] = trim($data['domicilio'] ?? '');
        
        // Teléfono: opcional, pero validar formato si existe
        $cleaned['telefono'] = trim($data['telefono'] ?? '');
        if (!empty($cleaned['telefono']) && !preg_match('/^[0-9\-\+\(\)\s]+$/', $cleaned['telefono'])) {
            $errors[] = 'El teléfono tiene un formato inválido';
        }
        
        // Correo: opcional, validar formato si existe
        $cleaned['correo'] = trim($data['correo'] ?? '');
        if (!empty($cleaned['correo']) && !filter_var($cleaned['correo'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'El correo electrónico es inválido';
        }
        
        // Fechas
        $cleaned['fechaIngreso'] = $data['fechaIngreso'] ?? null;
        $cleaned['fechaNacimiento'] = $data['fechaNacimiento'] ?? null;
        
        // Validar que fechaNacimiento no sea futura
        if ($cleaned['fechaNacimiento'] && strtotime($cleaned['fechaNacimiento']) > time()) {
            $errors[] = 'La fecha de nacimiento no puede ser futura';
        }
        
        // Sueldo: debe ser numérico positivo
        $cleaned['sueldoBasico'] = floatval($data['sueldoBasico'] ?? 0);
        if ($cleaned['sueldoBasico'] < 0) {
            $errors[] = 'El sueldo no puede ser negativo';
        }
        
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }
        
        return ['success' => true, 'data' => $cleaned];
    }

    /**
     * Verificar si documento es único
     */
    private function isDocumentoUnique($documento, $excludeId = null) {
        try {
            if ($excludeId) {
                $stmt = $this->db->prepare("SELECT id FROM empleado WHERE documento = :doc AND id != :id");
                $stmt->execute(['doc' => $documento, 'id' => $excludeId]);
            } else {
                $stmt = $this->db->prepare("SELECT id FROM empleado WHERE documento = :doc");
                $stmt->execute(['doc' => $documento]);
            }
            return $stmt->fetch() === false;
        } catch (PDOException $e) {
            return false;
        }
    }

    // ==================== CONSULTAS ESPECIALES ====================

    /**
     * Cumpleaños por mes
     */
    public function cumpleanosPorMes($mes) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM empleado WHERE MONTH(fechaNacimiento)=:mes ORDER BY DAY(fechaNacimiento)");
            $stmt->execute(['mes' => $mes]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            $this->logError('cumpleanosPorMes', $e->getMessage());
            return [];
        }
    }

    /**
     * Total de nómina
     */
    public function totalNomina() {
        try {
            $stmt = $this->db->query("SELECT SUM(sueldoBasico) as total FROM empleado");
            return $stmt->fetch();
        } catch (PDOException $e) {
            $this->logError('totalNomina', $e->getMessage());
            return ['total' => 0];
        }
    }

    /**
     * Total por sexo
     */
    public function totalPorSexo() {
        try {
            $stmt = $this->db->query("SELECT sexo, COUNT(*) as cantidad, SUM(sueldoBasico) as totalSueldo FROM empleado GROUP BY sexo");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            $this->logError('totalPorSexo', $e->getMessage());
            return [];
        }
    }

    /**
     * Contar empleados con salario mayor a X
     */
    public function countMayorSalario($salario_minimo) {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) as cantidad FROM empleado WHERE sueldoBasico > :sm");
            $stmt->execute(['sm' => $salario_minimo]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            $this->logError('countMayorSalario', $e->getMessage());
            return ['cantidad' => 0];
        }
    }

    /**
     * Log de errores
     */
    private function logError($method, $message) {
        $logFile = __DIR__ . '/../../logs/model_errors.log';
        $logDir = dirname($logFile);
        
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[{$timestamp}] Empleado::{$method} - {$message}\n";
        
        error_log($logMessage, 3, $logFile);
    }
}
