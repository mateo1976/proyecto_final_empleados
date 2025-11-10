<?php
// app/helpers.php
// Funciones helper globales para usar en las vistas

/**
 * Escapar HTML para prevenir XSS
 */
function e($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Obtener URL base del proyecto
 */
function base_url($path = '') {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $scriptName = $_SERVER['SCRIPT_NAME'];
    $basePath = str_replace('/index.php', '', $scriptName);
    
    return $protocol . '://' . $host . $basePath . ($path ? '/' . ltrim($path, '/') : '');
}

/**
 * Generar URL de ruta
 */
function route($route, $params = []) {
    $url = base_url('index.php') . '?route=' . $route;
    
    if (!empty($params)) {
        foreach ($params as $key => $value) {
            $url .= '&' . urlencode($key) . '=' . urlencode($value);
        }
    }
    
    return $url;
}

/**
 * Obtener valor antiguo del formulario
 */
function old($key, $default = '') {
    $old = $_SESSION['old'] ?? [];
    return e($old[$key] ?? $default);
}

/**
 * Verificar si hay errores
 */
function hasErrors() {
    return !empty($_SESSION['errors']);
}

/**
 * Obtener todos los errores
 */
function getErrors() {
    $errors = $_SESSION['errors'] ?? [];
    unset($_SESSION['errors']);
    return $errors;
}

/**
 * Obtener mensaje flash
 */
function getFlash() {
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $flash;
}

/**
 * Verificar si usuario está autenticado
 */
function isAuthenticated() {
    return isset($_SESSION['admin']);
}

/**
 * Obtener usuario autenticado
 */
function auth() {
    return $_SESSION['admin'] ?? null;
}

/**
 * Generar token CSRF
 */
function csrf_token() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Campo hidden de CSRF
 */
function csrf_field() {
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

/**
 * Formatear fecha
 */
function formatDate($date, $format = 'd/m/Y') {
    if (empty($date)) return '';
    return date($format, strtotime($date));
}

/**
 * Formatear dinero
 */
function formatMoney($amount, $decimals = 2) {
    return '$' . number_format($amount, $decimals, '.', ',');
}

/**
 * Verificar si ruta está activa
 */
function isActiveRoute($route) {
    $currentRoute = $_GET['route'] ?? 'dashboard';
    return $currentRoute === $route ? 'active' : '';
}

/**
 * Debug helper
 */
function dd(...$vars) {
    echo '<pre>';
    foreach ($vars as $var) {
        var_dump($var);
    }
    echo '</pre>';
    die();
}