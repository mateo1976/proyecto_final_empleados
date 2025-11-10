<?php
// public/index.php - Router principal mejorado
session_start();

// Configuración de errores según entorno
$env = getenv('APP_ENV') ?: 'development';
if ($env === 'production') {
    error_reporting(0);
    ini_set('display_errors', 0);
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

// Cargar dependencias
require_once __DIR__ . '/../app/config/Connection.php';
require_once __DIR__ . '/../app/helpers.php';
require_once __DIR__ . '/../app/controllers/EmpleadoController.php';

// Obtener ruta
$route = $_GET['route'] ?? 'login';

// Definir rutas públicas (que no requieren autenticación)
$publicRoutes = ['login', 'login_action'];

// Middleware de autenticación
if (!in_array($route, $publicRoutes)) {
    // Verificar si está autenticado
    if (!isAuthenticated()) {
        $_SESSION['flash'] = ['type' => 'error', 'message' => 'Debes iniciar sesión'];
        header('Location: ' . route('login'));
        exit;
    }
    
    // Verificar sesión expirada (2 horas de inactividad)
    $sessionTimeout = 7200; // 2 horas en segundos
    $lastActivity = $_SESSION['last_activity'] ?? time();
    
    if ((time() - $lastActivity) > $sessionTimeout) {
        session_destroy();
        session_start();
        $_SESSION['flash'] = ['type' => 'error', 'message' => 'Tu sesión ha expirado. Inicia sesión nuevamente.'];
        header('Location: ' . route('login'));
        exit;
    }
    
    // Actualizar última actividad
    $_SESSION['last_activity'] = time();
}

// Instanciar controlador
$empleadoController = new EmpleadoController();

// Router - Manejo de rutas
try {
    switch ($route) {
        // ==================== AUTENTICACIÓN ====================
        case 'login':
            if (isAuthenticated()) {
                header('Location: ' . route('dashboard'));
                exit;
            }
            require __DIR__ . '/../app/views/login.php';
            break;
            
        case 'login_action':
            require __DIR__ . '/login_action.php';
            break;
            
        case 'logout':
            // Log del logout
            if (isAuthenticated()) {
                $logFile = __DIR__ . '/../logs/auth.log';
                $timestamp = date('Y-m-d H:i:s');
                $logMessage = "[{$timestamp}] logout - Email: " . auth() . " - IP: " . $_SERVER['REMOTE_ADDR'] . "\n";
                error_log($logMessage, 3, $logFile);
            }
            
            session_destroy();
            session_start();
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Sesión cerrada exitosamente'];
            header('Location: ' . route('login'));
            break;
        
        // ==================== DASHBOARD ====================
        case 'dashboard':
            require __DIR__ . '/../app/views/dashboard.php';
            break;
        
        // ==================== EMPLEADOS ====================
        case 'empleados':
            $empleadoController->index();
            break;
            
        case 'empleados_create':
            $empleadoController->create();
            break;
            
        case 'empleados_store':
            $empleadoController->store($_POST);
            break;
            
        case 'empleados_edit':
            $id = $_GET['id'] ?? null;
            if (!$id) {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'ID no válido'];
                header('Location: ' . route('empleados'));
                exit;
            }
            $empleadoController->edit($id);
            break;
            
        case 'empleados_update':
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Método no permitido'];
                header('Location: ' . route('empleados'));
                exit;
            }
            $id = $_POST['id'] ?? null;
            $empleadoController->update($id, $_POST);
            break;
            
        case 'empleados_delete':
            $id = $_GET['id'] ?? null;
            if (!$id) {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'ID no válido'];
                header('Location: ' . route('empleados'));
                exit;
            }
            $empleadoController->delete($id);
            break;
        
        // ==================== OTRAS VISTAS ====================
        case 'cumpleanos':
            require __DIR__ . '/../app/views/cumpleanos.php';
            break;
            
        case 'finanzas':
            require __DIR__ . '/../app/views/finanzas.php';
            break;
            
        case 'nomina':
            require __DIR__ . '/../app/views/nomina.php';
            break;
        
        // ==================== 404 ====================
        default:
            http_response_code(404);
            ?>
            <!DOCTYPE html>
            <html lang="es">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>404 - Página no encontrada</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
                <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
            </head>
            <body class="bg-light">
                <div class="container d-flex justify-content-center align-items-center vh-100">
                    <div class="text-center">
                        <i class="bi bi-exclamation-triangle-fill text-warning" style="font-size: 100px;"></i>
                        <h1 class="display-1 fw-bold">404</h1>
                        <h2 class="mb-4">Página no encontrada</h2>
                        <p class="lead mb-4">La página que buscas no existe o fue movida.</p>
                        <a href="<?php echo route('dashboard'); ?>" class="btn btn-primary btn-lg">
                            <i class="bi bi-house"></i> Volver al Dashboard
                        </a>
                    </div>
                </div>
            </body>
            </html>
            <?php
            break;
    }
    
} catch (Exception $e) {
    // Manejo global de errores
    $logFile = __DIR__ . '/../logs/errors.log';
    $logDir = dirname($logFile);
    
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[{$timestamp}] Route: {$route} - Error: " . $e->getMessage() . "\n";
    error_log($logMessage, 3, $logFile);
    
    // Mostrar error amigable
    if ($env === 'production') {
        $_SESSION['flash'] = ['type' => 'error', 'message' => 'Ocurrió un error. Intenta nuevamente.'];
        header('Location: ' . route('dashboard'));
    } else {
        echo "<h1>Error del sistema</h1>";
        echo "<pre>" . $e->getMessage() . "</pre>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    }
}
