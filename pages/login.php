<?php
include_once '../app.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $role = authenticate($username, $password);

    if ($role) {
        if ($role === 'admin') {
            header('Location: inventory.php'); // Redirige al inventario
        } else {
            header('Location: index.php'); // Redirige al inicio
        }
        exit();
    } else {
        $error = "Credenciales incorrectas.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Login - Tienda RyE</title>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="container">
        <h2>Login</h2>

        <?php if (isset($error)): ?>
            <p style="color: red;"><?= $error ?></p>
        <?php endif; ?>

        <form method="POST" class="login-form">
            <label>Usuario:</label><br>
            <input type="text" name="username" required pattern="^[a-zA-Z0-9_]{4,20}$" maxlength="20" title="El nombre de usuario debe tener entre 4 y 20 caracteres alfanuméricos."><br><br>
            <label>Contraseña:</label><br>
            <input type="password" id="password" name="password" required maxlength="20"><br><br>
            <button type="submit">Iniciar sesión</button>
        </form>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
