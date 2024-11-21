<?php
include_once '../app.php'; 
if (!isAdmin()) {
    header('Location: login.php');
    exit();
}

include '../includes/db.php'; // Asegúrate de que la conexión a la base de datos esté configurada aquí

// Verificar si se ha enviado el formulario de búsqueda
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['search_name'])) {
    $search_name = mysqli_real_escape_string($conn, $_POST['search_name']);  // Sanitize input

    // Buscar usuario por nombre o correo
    $sql = "SELECT * FROM usuario WHERE Nombres LIKE '%$search_name%' OR Email LIKE '%$search_name%'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
    } else {
        echo "<p>No se encontraron usuarios con ese nombre.</p>";
    }
}

if (isset($_POST['edit_user'])) {
    if (isset($_POST['user_id'], $_POST['nombres'], $_POST['apellidos'], $_POST['email'], $_POST['telefono'], $_POST['tipo_usuario_id'], $_POST['descuento'])) {
        $user_id = $_POST['user_id'];
        $nombres = mysqli_real_escape_string($conn, $_POST['nombres']);
        $apellidos = mysqli_real_escape_string($conn, $_POST['apellidos']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $telefono = mysqli_real_escape_string($conn, $_POST['telefono']);
        $tipo_usuario_id = (int)$_POST['tipo_usuario_id'];
        $descuento = (float)$_POST['descuento'];  // Sanitize the descuento input

        // Verificar si el tipo de usuario existe en la tabla 'tipousuario'
        $check_tipo_usuario = "SELECT * FROM tipousuario WHERE IdTipoUsuario = '$tipo_usuario_id'";
        $result_check = mysqli_query($conn, $check_tipo_usuario);
        
        if (mysqli_num_rows($result_check) > 0) {
            // Si el tipo de usuario existe, proceder con la actualización
            $sql = "UPDATE usuario SET Nombres='$nombres', Apellidos='$apellidos', Email='$email', Telefono='$telefono', TipoUsuarioId='$tipo_usuario_id', Descuento='$descuento' WHERE IdUsuario='$user_id'";

            if (mysqli_query($conn, $sql)) {
                echo "<p>Usuario actualizado exitosamente.</p>";
            } else {
                echo "<p>Error al actualizar el usuario: " . mysqli_error($conn) . "</p>";
            }
        } else {
            echo "<p>Error: el tipo de usuario seleccionado no existe.</p>";
        }
    } else {
        echo "<p>Error: faltan datos necesarios para editar el usuario.</p>";
    }
}



// Si se ha enviado el formulario de eliminar
if (isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];

    $sql = "DELETE FROM usuario WHERE IdUsuario='$user_id'";

    if (mysqli_query($conn, $sql)) {
        echo "<p>Usuario eliminado exitosamente.</p>";
    } else {
        echo "<p>Error al eliminar el usuario: " . mysqli_error($conn) . "</p>";
    }
}

// Consulta para obtener todos los usuarios de tipo "Cliente" (TipoUsuarioId = 2)
$sql = "SELECT * FROM usuario WHERE TipoUsuarioId = 2";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Administrar usuario - Tienda RyE</title>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="container">
        <h2>Buscar usuario</h2>
        <form method="POST">
            <label>Nombre o Email</label><br>
            <input type="text" name="search_name" required><br><br>
            <button type="submit">Buscar</button>
        </form>

        <?php if (isset($user)): ?>
            <h2>Editar usuario</h2>
            <form method="POST">
    <input type="hidden" name="user_id"  value="<?php echo $user['IdUsuario']; ?>">

    <label>Nombre:</label><br>
    <input type="text" name="nombres" required maxlength="10" value="<?php echo $user['Nombres']; ?>" required><br><br>

    <label>Apellido:</label><br>
    <input type="text" name="apellidos" required maxlength="10" value="<?php echo $user['Apellidos']; ?>" required><br><br>

    <label>Email:</label><br>
    <input type="email" name="email"  value="<?php echo $user['Email']; ?>" required><br><br>

    <label>Teléfono:</label><br>
    <input type="text" name="telefono" required maxlength="10" value="<?php echo $user['Telefono']; ?>" required><br><br>

    <label>Tipo de usuario:</label><br>
    <select name="tipo_usuario_id" required>
        <option value="1" <?php echo ($user['TipoUsuarioId'] == 1) ? 'selected' : ''; ?>>Administrador</option>
        <option value="2" <?php echo ($user['TipoUsuarioId'] == 2) ? 'selected' : ''; ?>>Cliente</option>
    </select><br><br>

    <!-- Nuevo campo para descuento -->
    <label>Descuento:</label><br>
    <input type="number" name="descuento" value="<?php echo $user['Descuento']; ?>" step="0.01" min="0" max="100" required><br><br>

    <button type="submit" name="edit_user">Actualizar</button>
</form>



            <form method="POST">
                <input type="hidden" name="user_id" value="<?php echo $user['IdUsuario']; ?>">
                <button type="submit" name="delete_user">Eliminar usuario</button>
            </form>

        <?php endif; ?>
    </div>

    <div class="container usuarios">
        <h2>Lista de clientes</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($cliente = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $cliente['IdUsuario']; ?></td>
                        <td><?php echo $cliente['Nombres']; ?></td>
                        <td><?php echo $cliente['Apellidos']; ?></td>
                        <td><?php echo $cliente['Email']; ?></td>
                        <td><?php echo $cliente['Telefono']; ?></td>
                        <td>
                            <form method="POST" action="">
                                <input type="hidden" name="user_id" value="<?php echo $cliente['IdUsuario']; ?>">
                                
                                <button type="submit" name="delete_user">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
