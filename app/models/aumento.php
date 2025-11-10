<?php
// app/models/Aumento.php
require_once __DIR__ . '/../config/Connection.php';

class Aumento {
    private $db;
    
    public function __construct() {
        $this->db = Connection::getInstance();
    }

    /**
     * Aplicar aumento a todos los empleados
     */
    public function aplicarAumentoGlobal($porcentaje, $admin_id) {
        try {
            $this->db->beginTransaction();
            
            // Obtener todos los empleados
            $stmt = $this->db->query("SELECT id, nombre, sueldoBasico FROM empleado");
            $empleados = $stmt->fetchAll();
            
            $empleadosAfectados = [];
            
            foreach ($empleados as $emp) {
                $sueldoAnterior = $emp['sueldoBasico'];
                $incremento = ($sueldoAnterior * $porcentaje) / 100;
                $sueldoNuevo = $sueldoAnterior + $incremento;
                
                // Actualizar sueldo
                $update = $this->db->prepare("UPDATE empleado SET sueldoBasico = :nuevo WHERE id = :id");
                $update->execute(['nuevo' => $sueldoNuevo, 'id' => $emp['id']]);
                
                // Registrar en historial
                $this->registrarAumento(
                    $emp['id'],
                    $sueldoAnterior,
                    $sueldoNuevo,
                    $porcentaje,
                    'global',
                    $admin_id
                );
                
                $empleadosAfectados[] = [
                    'nombre' => $emp['nombre'],
                    'anterior' => $sueldoAnterior,
                    'nuevo' => $sueldoNuevo,
                    'incremento' => $incremento
                ];
            }
            
            $this->db->commit();
            
            return [
                'success' => true,
                'afectados' => count($empleadosAfectados),
                'detalles' => $empleadosAfectados
            ];
            
        } catch (PDOException $e) {
            $this->db->rollBack();
            $this->logError('aplicarAumentoGlobal', $e->getMessage());
            return ['success' => false, 'errors' => ['Error al aplicar aumentos']];
        }
    }

    /**
     * Aplicar aumento a empleados seleccionados
     */
    public function aplicarAumentoSelectivo($empleadosIds, $porcentaje, $admin_id) {
        try {
            $this->db->beginTransaction();
            
            $empleadosAfectados = [];
            
            foreach ($empleadosIds as $id) {
                // Obtener empleado
                $stmt = $this->db->prepare("SELECT id, nombre, sueldoBasico FROM empleado WHERE id = :id");
                $stmt->execute(['id' => $id]);
                $emp = $stmt->fetch();
                
                if (!$emp) continue;
                
                $sueldoAnterior = $emp['sueldoBasico'];
                $incremento = ($sueldoAnterior * $porcentaje) / 100;
                $sueldoNuevo = $sueldoAnterior + $incremento;
                
                // Actualizar sueldo
                $update = $this->db->prepare("UPDATE empleado SET sueldoBasico = :nuevo WHERE id = :id");
                $update->execute(['nuevo' => $sueldoNuevo, 'id' => $id]);
                
                // Registrar en historial
                $this->registrarAumento(
                    $id,
                    $sueldoAnterior,
                    $sueldoNuevo,
                    $porcentaje,
                    'selectivo',
                    $admin_id
                );
                
                $empleadosAfectados[] = [
                    'nombre' => $emp['nombre'],
                    'anterior' => $sueldoAnterior,
                    'nuevo' => $sueldoNuevo,
                    'incremento' => $incremento
                ];
            }
            
            $this->db->commit();
            
            return [
                'success' => true,
                'afectados' => count($empleadosAfectados),
                'detalles' => $empleadosAfectados
            ];
            
        } catch (PDOException $e) {
            $this->db->rollBack();
            $this->logError('aplicarAumentoSelectivo', $e->getMessage());
            return ['success' => false, 'errors' => ['Error al aplicar aumentos']];
        }
    }

    /**
     * Aplicar aumento por rango de sueldos
     */
    public function aplicarAumentoPorRango($sueldoMin, $sueldoMax, $porcentaje, $admin_id) {
        try {
            $this->db->beginTransaction();
            
            // Obtener empleados en el rango
            $stmt = $this->db->prepare("
                SELECT id, nombre, sueldoBasico 
                FROM empleado 
                WHERE sueldoBasico BETWEEN :min AND :max
            ");
            $stmt->execute(['min' => $sueldoMin, 'max' => $sueldoMax]);
            $empleados = $stmt->fetchAll();
            
            $empleadosAfectados = [];
            
            foreach ($empleados as $emp) {
                $sueldoAnterior = $emp['sueldoBasico'];
                $incremento = ($sueldoAnterior * $porcentaje) / 100;
                $sueldoNuevo = $sueldoAnterior + $incremento;
                
                // Actualizar sueldo
                $update = $this->db->prepare("UPDATE empleado SET sueldoBasico = :nuevo WHERE id = :id");
                $update->execute(['nuevo' => $sueldoNuevo, 'id' => $emp['id']]);
                
                // Registrar en historial
                $this->registrarAumento(
                    $emp['id'],
                    $sueldoAnterior,
                    $sueldoNuevo,
                    $porcentaje,
                    'por_rango',
                    $admin_id
                );
                
                $empleadosAfectados[] = [
                    'nombre' => $emp['nombre'],
                    'anterior' => $sueldoAnterior,
                    'nuevo' => $sueldoNuevo,
                    'incremento' => $incremento
                ];
            }
            
            $this->db->commit();
            
            return [
                'success' => true,
                'afectados' => count($empleadosAfectados),
                'detalles' => $empleadosAfectados
            ];
            
        } catch (PDOException $e) {
            $this->db->rollBack();
            $this->logError('aplicarAumentoPorRango', $e->getMessage());
            return ['success' => false, 'errors' => ['Error al aplicar aumentos']];
        }
    }

    /**
     * Registrar aumento en historial
     */
    private function registrarAumento($empleado_id, $sueldoAnterior, $sueldoNuevo, $porcentaje, $tipo, $admin_id) {
        $sql = "INSERT INTO historial_aumentos 
                (empleado_id, sueldo_anterior, sueldo_nuevo, porcentaje, tipo_aumento, admin_id, fecha) 
                VALUES (:emp_id, :anterior, :nuevo, :porcentaje, :tipo, :admin_id, NOW())";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'emp_id' => $empleado_id,
            'anterior' => $sueldoAnterior,
            'nuevo' => $sueldoNuevo,
            'porcentaje' => $porcentaje,
            'tipo' => $tipo,
            'admin_id' => $admin_id
        ]);
    }

    /**
     * Obtener historial de aumentos
     */
    public function obtenerHistorial($limite = 50) {
        try {
            $stmt = $this->db->prepare("
                SELECT h.*, e.nombre as empleado_nombre, e.documento 
                FROM historial_aumentos h 
                INNER JOIN empleado e ON h.empleado_id = e.id 
                ORDER BY h.fecha DESC 
                LIMIT :limite
            ");
            $stmt->execute(['limite' => $limite]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            $this->logError('obtenerHistorial', $e->getMessage());
            return [];
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
        $logMessage = "[{$timestamp}] Aumento::{$method} - {$message}\n";
        
        error_log($logMessage, 3, $logFile);
    }
}