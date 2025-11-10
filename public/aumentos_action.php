<?php
// public/aumentos_action.php
session_start();
require_once __DIR__ . '/../app/config/Connection.php';
require_once __DIR__ . '/../app/helpers.php';
require_once __DIR__ . '/../app/models/Aumento.php';

// Verificar autenticación
if (!isAuthenticated()) {
    $_SESSION['flash'] = ['type' => 'error', 'message' => 'Debes iniciar sesión'];
    header('Location: ' . route('login'));
    exit;
}

// Verificar método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['flash'] = ['type' => 'error', 'message' => 'Método no permitido'];
    header('Location: ' . route('finanzas'));
    exit;
}

// Verificar CSRF
$csrfToken = $_POST['csrf_token'] ?? '';
if (empty($csrfToken) || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $csrfToken)) {
    $_SESSION['flash'] = ['type' => 'error', 'message' => 'Token de seguridad inválido'];
    header('Location: ' . route('finanzas'));
    exit;
}
unset($_SESSION['csrf_token']);

// Obtener datos
$tipo = $_POST['tipo'] ?? '';
$porcentaje = floatval($_POST['porcentaje'] ?? 0);
$adminId = $_SESSION['admin_id'] ?? 0;

// Validar porcentaje
if ($porcentaje <= 0 || $porcentaje > 100) {
    $_SESSION['flash'] = ['type' => 'error', 'message' => 'El porcentaje debe estar entre 0.01 y 100'];
    header('Location: ' . route('finanzas'));
    exit;
}

$aumentoModel = new Aumento();

try {
    switch ($tipo) {
        case 'global':
            $resultado = $aumentoModel->aplicarAumentoGlobal($porcentaje, $adminId);
            break;
            
        case 'selectivo':
            $empleadosIds = explode(',', $_POST['empleados_ids'] ?? '');
            $empleadosIds = array_filter($empleadosIds, 'is_numeric');
            
            if (empty($empleadosIds)) {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'No hay empleados seleccionados'];
                header('Location: ' . route('finanzas'));
                exit;
            }
            
            $resultado = $aumentoModel->aplicarAumentoSelectivo($empleadosIds, $porcentaje, $adminId);
            break;
            
        case 'rango':
            $sueldoMin = floatval($_POST['sueldo_min'] ?? 0);
            $sueldoMax = floatval($_POST['sueldo_max'] ?? 0);
            
            if ($sueldoMin < 0 || $sueldoMax < $sueldoMin) {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Rango de sueldos inválido'];
                header('Location: ' . route('finanzas'));
                exit;
            }
            
            $resultado = $aumentoModel->aplicarAumentoPorRango($sueldoMin, $sueldoMax, $porcentaje, $adminId);
            break;
            
        default:
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Tipo de aumento no válido'];
            header('Location: ' . route('finanzas'));
            exit;
    }
    
    if ($resultado['success']) {
        $mensaje = "Aumento del {$porcentaje}% aplicado exitosamente a {$resultado['afectados']} empleado(s)";
        $_SESSION['flash'] = ['type' => 'success', 'message' => $mensaje];
        
        // Guardar detalles en sesión para mostrar resumen
        $_SESSION['resultado_aumento'] = $resultado['detalles'];
        
        // Log del aumento
        logAumento($tipo, $porcentaje, $resultado['afectados'], $adminId);
    } else {
        $_SESSION['errors'] = $resultado['errors'];
    }
    
} catch (Exception $e) {
    $_SESSION['flash'] = ['type' => 'error', 'message' => 'Error al aplicar aumentos'];
    logError('aumentos_action', $e->getMessage());
}

header('Location: ' . route('finanzas'));
exit;

/**
 * Log de aumentos aplicados
 */
function logAumento($tipo, $porcentaje, $afectados, $adminId) {
    $logFile = __DIR__ . '/../logs/aumentos.log';
    $logDir = dirname($logFile);
    
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[{$timestamp}] Tipo: {$tipo} - Porcentaje: {$porcentaje}% - Afectados: {$afectados} - Admin ID: {$adminId}\n";
    
    error_log($logMessage, 3, $logFile);
}

/**
 * Log de errores
 */
function logError($context, $message) {
    $logFile = __DIR__ . '/../logs/errors.log';
    $logDir = dirname($logFile);
    
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[{$timestamp}] {$context} - {$message}\n";
    
    error_log($logMessage, 3, $logFile);
}