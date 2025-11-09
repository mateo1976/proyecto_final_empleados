<?php
// seed/create_admin.php
require_once __DIR__ . '/../app/config/Connection.php';
$pdo = Connection::getInstance();

$email = 'admin@gmail.com';
$pass = 'admin123';
$hash = password_hash($pass, PASSWORD_DEFAULT);

// check exists
$stmt = $pdo->prepare("SELECT * FROM administrador WHERE email = :email");
$stmt->execute(['email'=>$email]);
if ($stmt->fetch()) {
    echo "Admin ya existe.\n";
    exit;
}

$ins = $pdo->prepare("INSERT INTO administrador (email,password) VALUES (:email,:password)");
if ($ins->execute(['email'=>$email,'password'=>$hash])) {
    echo "Admin creado: $email\n";
} else {
    echo "Error al crear admin\n";
}
