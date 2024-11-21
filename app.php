<?php
session_start();
// Función para verificar si el usuario está logueado
function isUserLoggedIn() {
    return isset($_SESSION['user']); // Esto verificará si la variable de sesión 'user' está seteada
}
function isAdmin() {
    return isset($_SESSION['user']) && $_SESSION['role'] === 'admin';
}

function authenticate($username, $password) {
    // Usuarios ficticios
    $users = [
        'admin' => ['password' => '1234', 'role' => 'admin'],
        'user' => ['password' => '5678', 'role' => 'user']
    ];

    if (isset($users[$username]) && $users[$username]['password'] === $password) {
        $_SESSION['user'] = $username;
        $_SESSION['role'] = $users[$username]['role'];
        return $users[$username]['role']; // Retorna el rol
    }
    return false;
}

function logout() {
    session_destroy();
    header('Location: login.php');
    exit();
}
?>