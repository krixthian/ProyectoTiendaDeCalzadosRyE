<?php
include_once '../app.php'; // Esto debe tener las funciones de control de acceso, como isAdmin()
if (!isAdmin()) {
    header('Location: login.php');
    exit();
}

include '../includes/db.php'; // Asegúrate de que este archivo contiene la conexión a la base de datos

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $contra = $_POST['contra'];
    $tipo_usuario_id = $_POST['tipo_usuario_id'];

    // Sanitizar y validar los datos
    $nombres = mysqli_real_escape_string($conn, $nombres);
    $apellidos = mysqli_real_escape_string($conn, $apellidos);
    $email = mysqli_real_escape_string($conn, $email);
    $telefono = mysqli_real_escape_string($conn, $telefono);
    $contra = mysqli_real_escape_string($conn, $contra);
    $tipo_usuario_id = (int)$tipo_usuario_id;

    // Cifrar la contraseña antes de almacenarla
    $hashed_contra = password_hash($contra, PASSWORD_DEFAULT);

    // Insertar el usuario en la base de datos
    $sql = "INSERT INTO usuario (Nombres, Apellidos, Email, Telefono, Contra, TipoUsuarioId) 
            VALUES ('$nombres', '$apellidos', '$email', '$telefono', '$hashed_contra', '$tipo_usuario_id')";

    if (mysqli_query($conn, $sql)) {
        echo "<p>Usuario agregado exitosamente.</p>";
    } else {
        echo "<p>Error al agregar el usuario: " . mysqli_error($conn) . "</p>";
    }
}
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Agregar usuario - Tienda RyE</title>
</head>
<body>
    <?php 
        include '../includes/header.php'; 
        include '../includes/db.php'; // Asegúrate de que la conexión a la base de datos esté configurada aquí
    ?>

    <div class="container">
        <h2>Agregar usuario</h2>
        <form method="POST" action="" onsubmit="return validateForm()">
    <label>Nombre:</label><br>
    <input type="text" name="nombres"  id="nombres" required maxlength="10"><br><br>

    <label>Apellido:</label><br>
    <input type="text" name="apellidos" id="apellidos" required maxlength="10"><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" id="email" required><br><br>

    <label>Teléfono:</label><br>
    <input type="text" name="telefono" id="telefono" required maxlength="10"><br><br>

    <label>Contraseña:</label><br>
    <input type="password" name="contra" id="contra" required><br><br>

    <label>Tipo de usuario:</label><br>
    <select name="tipo_usuario_id" id="tipo_usuario_id" required>
        <option value="2">Cliente</option>
        <option value="1">Administrador</option>
    </select><br><br>

    <button type="submit">Agregar</button>
</form>
<script>
    // Validación del formulario en el lado del cliente
    function validateForm() {
        // Validar el nombre
        const nombre = document.getElementById("nombres").value;
        if (nombre.trim() === "") {
            alert("Por favor, ingrese un nombre válido.");
            return false;
        }

        // Validar apellido
        const apellido = document.getElementById("apellidos").value;
        if (apellido.trim() === "") {
            alert("Por favor, ingrese un apellido válido.");
            return false;
        }

        // Validar email
        const email = document.getElementById("email").value;
        const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        if (!emailRegex.test(email)) {
            alert("Por favor, ingrese un email válido.");
            return false;
        }

        // Validar teléfono (Solo números, longitud 10)
        const telefono = document.getElementById("telefono").value;
        const telefonoRegex = /^\d{10}$/;
        if (!telefonoRegex.test(telefono)) {
            alert("Por favor, ingrese un número de teléfono válido (10 dígitos).");
            return false;
        }

        // Validar contraseña (mínimo 6 caracteres)
        const contra = document.getElementById("contra").value;
        if (contra.length < 6) {
            alert("La contraseña debe tener al menos 6 caracteres.");
            return false;
        }

        return true; // Si todo está bien, se envía el formulario
    }
</script>
    </div>

    <?php 
        include '../includes/footer.php'; 
    ?>
</body>
</html>


