<?php

session_start();

$admin_username = 'admin'; // Cambia esto con tu nombre de usuario admin
$admin_password = 'admin123'; // Cambia esto con tu contraseña admin

// Inicializar variables de bloqueo e intentos fallidos
if (!isset($_SESSION['failed_attempts'])) {
    $_SESSION['failed_attempts'] = 0;
}
if (!isset($_SESSION['block_until'])) {
    $_SESSION['block_until'] = 0;
}

function isUserLoggedIn() {
    return isset($_SESSION['user']);
}
function isAdmin() {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
}

function authenticate($username, $password) {
    $conn = new mysqli('localhost', 'root', '', 'zapateria');

    if ($conn->connect_error) {
        die('Error de conexión: ' . $conn->connect_error);
    }

    $sql = 'SELECT Contra FROM usuario WHERE Nombres = ?';
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die('Error al preparar la consulta: ' . $conn->error);
    }

    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user'] = $username;
            $_SESSION['failed_attempts'] = 0; // Reiniciar contador de intentos fallidos
            $stmt->close();
            $conn->close();
            return true;
        }
    }

    $stmt->close();
    $conn->close();
    return false;
}

function logout() {
    session_destroy();
    header('Location: login.php');
    exit();
}

?>
