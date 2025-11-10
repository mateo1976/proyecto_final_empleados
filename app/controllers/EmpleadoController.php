<?php
// app/controllers/EmpleadoController.php
require_once __DIR__ . '/../models/Empleado.php';

class EmpleadoController {
    private $model;
    private $baseUrl;
    
    public function __construct() {
        $this->model = new Empleado();
        $this->baseUrl = $this->getBaseUrl();
        
        // Iniciar sesión si no está iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Listar todos los empleados
     */
    public function index() {
        $empleados = $this->model->all();
        require __DIR__ . '/../views/empleados/index.php';
    }

    /**
     * Mostrar formulario de creación
     */
    public function create() {
        // Generar token CSRF
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        require __DIR__ . '/../views/empleados/create.php';
    }

    /**
     * Guardar nuevo empleado
     */
    public function store($data) {
        // Verificar método POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->setFlash('error', 'Método no permitido');
            $this->redirect('empleados');
            return;
        }
        
        // Verificar token CSRF
        if (!$this->validateCsrfToken($data['csrf_token'] ?? '')) {
            $this->setFlash('error', 'Token de seguridad inválido');
            $this->redirect('empleados_create');
            return;
        }
        
        // Intentar crear empleado
        $result = $this->model->create($data);
        
        if ($result['success']) {
            $this->setFlash('success', 'Empleado creado exitosamente');
            $this->redirect('empleados');
        } else {
            // Guardar errores y datos antiguos en sesión
            $_SESSION['errors'] = $result['errors'];
            $_SESSION['old'] = $data;
            $this->redirect('empleados_create');
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id) {
        if (empty($id)) {
            $this->setFlash('error', 'ID de empleado no válido');
            $this->redirect('empleados');
            return;
        }
        
        $empleado = $this->model->find($id);
        
        if (!$empleado) {
            $this->setFlash('error', 'Empleado no encontrado');
            $this->redirect('empleados');
            return;
        }
        
        // Generar token CSRF
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        
        require __DIR__ . '/../views/empleados/edit.php';
    }

    /**
     * Actualizar empleado
     */
    public function update($id, $data) {
        // Verificar método POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->setFlash('error', 'Método no permitido');
            $this->redirect('empleados');
            return;
        }
        
        // Verificar token CSRF
        if (!$this->validateCsrfToken($data['csrf_token'] ?? '')) {
            $this->setFlash('error', 'Token de seguridad inválido');
            $this->redirect('empleados_edit', ['id' => $id]);
            return;
        }
        
        // Verificar que el empleado existe
        $empleado = $this->model->find($id);
        if (!$empleado) {
            $this->setFlash('error', 'Empleado no encontrado');
            $this->redirect('empleados');
            return;
        }
        
        // Intentar actualizar
        $result = $this->model->update($id, $data);
        
        if ($result['success']) {
            $this->setFlash('success', 'Empleado actualizado exitosamente');
            $this->redirect('empleados');
        } else {
            $_SESSION['errors'] = $result['errors'];
            $_SESSION['old'] = $data;
            $this->redirect('empleados_edit', ['id' => $id]);
        }
    }

    /**
     * Eliminar empleado
     */
    public function delete($id) {
        if (empty($id)) {
            $this->setFlash('error', 'ID de empleado no válido');
            $this->redirect('empleados');
            return;
        }
        
        // Verificar que el empleado existe
        $empleado = $this->model->find($id);
        if (!$empleado) {
            $this->setFlash('error', 'Empleado no encontrado');
            $this->redirect('empleados');
            return;
        }
        
        // Intentar eliminar
        $result = $this->model->delete($id);
        
        if ($result['success']) {
            $this->setFlash('success', 'Empleado eliminado exitosamente');
        } else {
            $this->setFlash('error', 'Error al eliminar el empleado');
        }
        
        $this->redirect('empleados');
    }

    // ==================== HELPERS ====================

    /**
     * Establecer mensaje flash
     */
    private function setFlash($type, $message) {
        $_SESSION['flash'] = [
            'type' => $type,
            'message' => $message
        ];
    }

    /**
     * Validar token CSRF
     */
    private function validateCsrfToken($token) {
        if (empty($token) || empty($_SESSION['csrf_token'])) {
            return false;
        }
        
        $valid = hash_equals($_SESSION['csrf_token'], $token);
        
        // Eliminar token usado (one-time token)
        unset($_SESSION['csrf_token']);
        
        return $valid;
    }

    /**
     * Redireccionar a ruta
     */
    private function redirect($route, $params = []) {
        $url = $this->baseUrl . '?route=' . $route;
        
        if (!empty($params)) {
            foreach ($params as $key => $value) {
                $url .= '&' . $key . '=' . urlencode($value);
            }
        }
        
        header('Location: ' . $url);
        exit;
    }

    /**
     * Obtener base URL dinámica
     */
    private function getBaseUrl() {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        
        // Detectar ruta base del proyecto
        $scriptName = $_SERVER['SCRIPT_NAME'];
        $basePath = str_replace('/index.php', '', $scriptName);
        
        return $protocol . '://' . $host . $basePath . '/index.php';
    }

    /**
     * Obtener errores de validación
     */
    public static function getErrors() {
        $errors = $_SESSION['errors'] ?? [];
        unset($_SESSION['errors']);
        return $errors;
    }

    /**
     * Obtener datos antiguos del formulario
     */
    public static function old($key, $default = '') {
        $old = $_SESSION['old'] ?? [];
        $value = $old[$key] ?? $default;
        
        // Limpiar old data después de usarla
        if (isset($_SESSION['old'])) {
            unset($_SESSION['old']);
        }
        
        return htmlspecialchars($value);
    }

    /**
     * Obtener y limpiar mensaje flash
     */
    public static function getFlash() {
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        return $flash;
    }
}
