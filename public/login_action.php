<?php
// public/login_action.php
session_start();
require_once __DIR__ . '/../app/config/Connection.php';
$pdo = Connection::getInstance();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $pass = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM administrador WHERE email = :email LIMIT 1");
    $stmt->execute(['email'=>$email]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($pass, $admin['password'])) {
        $_SESSION['admin'] = $admin['email'];
        header('Location: index.php?route=dashboard');
        exit;
    } else {
        header('Location: index.php?route=login');
        exit;
    }
}
