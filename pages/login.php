<?php
include_once '../app.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    
    $current_time = time();
    if (isset($_SESSION['block_until']) && $current_time < $_SESSION['block_until']) {
        $time_remaining = $_SESSION['block_until'] - $current_time;
        $error = "Has excedido el número de intentos. Intenta nuevamente en $time_remaining segundos.";
    } else {
        
        $_SESSION['block_until'] = null;

        
        $conn = new mysqli('localhost', 'root', '', 'zapateria');
        if ($conn->connect_error) {
            die('Error de conexión: ' . $conn->connect_error);
        }

        
        if ($username === $admin_username && $password === $admin_password) {
            session_start();
            $_SESSION['user'] = $username;
            $_SESSION['is_admin'] = true;
            $_SESSION['failed_attempts'] = 0; 
            header('Location: inventory.php');
            exit();
        }

        
        $sql_usuario = "SELECT Nombres, Contra, TipoUsuarioId FROM usuario WHERE LOWER(Nombres) = LOWER(?)";
        $stmt_usuario = $conn->prepare($sql_usuario);

        if ($stmt_usuario) {
            $stmt_usuario->bind_param('s', $username);
            $stmt_usuario->execute();
            $result_usuario = $stmt_usuario->get_result();

            if ($result_usuario->num_rows > 0) {
                $user = $result_usuario->fetch_assoc();
                if (password_verify($password, $user['Contra'])) {
                    session_start();
                    $_SESSION['user'] = $username;
                    $_SESSION['failed_attempts'] = 0; 

                    
                    if ($user['TipoUsuarioId'] == 1) {
                        $_SESSION['is_admin'] = true;
                        header('Location: adminusuario.php');
                    } else {
                        $_SESSION['is_admin'] = false;
                        header('Location: index.php');
                    }
                    exit();
                } else {
                    $error = "Contraseña incorrecta.";
                }
            } else {
                $error = "Usuario no encontrado.";
            }
            $stmt_usuario->close();
        } else {
            $error = "Error al buscar el usuario.";
        }

        
        $_SESSION['failed_attempts'] = $_SESSION['failed_attempts'] ?? 0;
        $_SESSION['failed_attempts']++;
        if ($_SESSION['failed_attempts'] >= 5) {
            $_SESSION['block_until'] = $current_time + 10; 
            $_SESSION['failed_attempts'] = 0; 
            $time_remaining = 10;
            $error = "Demasiados intentos fallidos. Intenta nuevamente en $time_remaining segundos.";
        }
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
    <script>
        function actualizarContador(segundos) {
            const mensaje = document.getElementById('contador');
            const intervalo = setInterval(() => {
                if (segundos <= 0) {
                    mensaje.textContent = '';
                    clearInterval(intervalo);
                } else {
                    mensaje.textContent = `Espera ${segundos--} segundos para volver a intentarlo.`;
                }
            }, 1000);
        }
    </script>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="container">
        <h2>Login</h2>

        <?php if (isset($error)): ?>
            <p style="color: red;" id="contador"><?= $error ?></p>
            <?php if (isset($time_remaining)): ?>
                <script>
                    actualizarContador(<?= $time_remaining ?>);
                </script>
            <?php endif; ?>
        <?php endif; ?>

        <form method="POST" class="login-form">
            <label>Nombre de Usuario:</label><br>
            <input type="text" name="username" required><br><br>

            <label>Contraseña:</label><br>
            <input type="password" name="password" required><br><br>

            <button type="submit">Iniciar Sesión</button>
        </form>

        <p>¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a></p>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
