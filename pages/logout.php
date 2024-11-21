<?php
include_once '../app.php';
logout(); // Llama a la función para destruir la sesión y redirigir
if (!isUserLoggedIn()) {
    header('Location: login.php'); // Redirige a login si no está logueado
    exit();
}
?>
