<?php
// public/index.php - simple router and login
session_start();
require_once __DIR__ . '/../app/config/Connection.php';

$base = '/proyecto_final_empleados/public';

$route = $_GET['route'] ?? 'login';

if ($route === 'login') {
    // login page
    require __DIR__ . '/../app/views/login.php';
    exit;
}

// check auth
if (!isset($_SESSION['admin'])) {
    header('Location: '.$base.'?route=login');
    exit;
}

// simple router for pages
require_once __DIR__ . '/../app/controllers/EmpleadoController.php';
$ctrl = new EmpleadoController();

switch ($route) {
    case 'dashboard':
        require __DIR__ . '/../app/views/dashboard.php';
        break;
    case 'empleados':
        $ctrl->index();
        break;
    case 'empleados_create':
        $ctrl->create();
        break;
    case 'empleados_store':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ctrl->store($_POST);
        }
        break;
    case 'empleados_edit':
        $id = $_GET['id'] ?? null;
        $ctrl->edit($id);
        break;
    case 'empleados_update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $ctrl->update($id, $_POST);
        }
        break;
    case 'empleados_delete':
        $id = $_GET['id'] ?? null;
        $ctrl->delete($id);
        break;
    case 'cumpleanos':
        require __DIR__ . '/../app/views/cumpleanos.php';
        break;
    case 'finanzas':
        require __DIR__ . '/../app/views/finanzas.php';
        break;
    case 'nomina':
        require __DIR__ . '/../app/views/nomina.php';
        break;
    case 'logout':
        session_destroy();
        header('Location: '.$base.'?route=login');
        break;
    default:
        require __DIR__ . '/../app/views/dashboard.php';
}
