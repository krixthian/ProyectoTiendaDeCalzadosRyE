<?php
include_once '../app.php'; // Incluye las funciones de conexión a la base de datos

// Verifica si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $password = $_POST['password'];

    // Validación de la contraseña
    if (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,20}$/', $password)) {
        $error = 'La contraseña debe tener al menos una letra mayúscula, un número y un carácter especial, y debe tener entre 6 y 20 caracteres.';
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Encripta la contraseña

        // Conecta a la base de datos
        $conn = new mysqli('localhost', 'root', '', 'zapateria');

        // Verifica la conexión
        if ($conn->connect_error) {
            die('Error de conexión: ' . $conn->connect_error);
        }

        // Prepara la consulta SQL para insertar los datos
        $sql = "INSERT INTO usuario (Nombres, Apellidos, Email, Telefono, Contra) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            // Vincula los parámetros
            $stmt->bind_param('sssss', $nombres, $apellidos, $email, $telefono, $hashed_password);

            // Ejecuta la consulta
            if ($stmt->execute()) {
                // Registro exitoso, redirige al login
                header('Location: login.php?success=1');
                exit();
            } else {
                $error = 'Error al registrar el usuario: ' . $stmt->error;
            }

            // Cierra la consulta
            $stmt->close();
        } else {
            $error = 'Error al preparar la consulta: ' . $conn->error;
        }

        // Cierra la conexión
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Registro - Tienda RyE</title>
    <script>
        function validarFormulario() {
            const password = document.querySelector('input[name="password"]').value;
            const regex = /^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,20}$/;
            if (!regex.test(password)) {
                alert('La contraseña debe tener al menos una letra mayúscula, un número y un carácter especial, y debe tener entre 6 y 20 caracteres.');
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="container">
        <h2>Registro</h2>

        <?php if (isset($error)): ?>
            <p style="color: red;"><?= $error ?></p>
        <?php endif; ?>

        <form method="POST" class="registro-form" onsubmit="return validarFormulario()">
            <label>Nombres:</label><br>
            <input type="text" name="nombres" required maxlength="50"><br><br>

            <label>Apellidos:</label><br>
            <input type="text" name="apellidos" required maxlength="50"><br><br>

            <label>Email:</label><br>
            <input type="email" name="email" required maxlength="100"><br><br>

            <label>Teléfono:</label><br>
            <input type="tel" name="telefono" required pattern="^[0-9]{8,15}$" maxlength="15" title="Debe contener entre 8 y 15 dígitos."><br><br>

            <label>Contraseña:</label><br>
            <input type="password" name="password" required minlength="6" maxlength="20"><br><br>

            <button type="submit">Registrarse</button>
        </form>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
