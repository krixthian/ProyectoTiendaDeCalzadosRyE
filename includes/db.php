<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Zapateria";

// Crear conexión
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Verificar la conexión
if (!$conn) {
    die("Conexión fallida: " . mysqli_connect_error());
}
?>
