<?php
include_once '../app.php';
if (!isAdmin()) {
    header('Location: login.php');
    exit();
}

include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize inputs
    $nombres = mysqli_real_escape_string($conn, $_POST['nombres']);
    $apellidos = mysqli_real_escape_string($conn, $_POST['apellidos']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $telefono = mysqli_real_escape_string($conn, $_POST['telefono']);
    
    // Hash the password
    $hashed_password = password_hash($_POST['contra'], PASSWORD_DEFAULT);
    
    // Determine ROL based on tipo_usuario_id
    $rol = $_POST['tipo_usuario_id'] == 1 ? 1 : 0;

    $prefix = $rol == 0 ? 'ADM-' : 'VEN-';
    $nombres = $prefix . $nombres;
    
    $sql = "INSERT INTO empleados (nombre, apellido, email, telefono, password, ROL) 
            VALUES ('$nombres', '$apellidos', '$email', '$telefono', '$hashed_password', '$rol')";

    if (mysqli_query($conn, $sql)) {
        echo "<p>Empleado agregado exitosamente.</p>";
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
    <title>Agregar Empleado - Tienda RyE</title>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="container">
        <h2>Agregar Empleado</h2>
        <form method="POST" action="" onsubmit="return validateForm()">
            <label>Nombre:</label><br>
            <input type="text" name="nombres" id="nombres" required maxlength="50"><br><br>

            <label>Apellido:</label><br>
            <input type="text" name="apellidos" id="apellidos" required maxlength="50"><br><br>

            <label>Email:</label><br>
            <input type="email" name="email" id="email" required maxlength="100"><br><br>

            <label>Teléfono:</label><br>
            <input type="text" name="telefono" id="telefono" maxlength="8"><br><br>

            <label>Contraseña:</label><br>
            <input type="password" name="contra" id="contra" required><br><br>

            <label>Rol:</label><br>
            <select name="tipo_usuario_id" id="tipo_usuario_id" required>
                <option value="1">Administrador</option>
                <option value="0">Ventas</option>
            </select><br><br>

            <button type="submit">Agregar</button>
        </form>

        <script>
        function validateForm() {
            const nombre = document.getElementById("nombres").value.trim();
            const apellido = document.getElementById("apellidos").value.trim();
            const email = document.getElementById("email").value.trim();
            const contra = document.getElementById("contra").value;

            if (nombre === "") {
                alert("Por favor, ingrese un nombre válido.");
                return false;
            }

            if (apellido === "") {
                alert("Por favor, ingrese un apellido válido.");
                return false;
            }

            const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            if (!emailRegex.test(email)) {
                alert("Por favor, ingrese un email válido.");
                return false;
            }

            if (contra.length < 6) {
                alert("La contraseña debe tener al menos 6 caracteres.");
                return false;
            }

            return true;
        }
        </script>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>