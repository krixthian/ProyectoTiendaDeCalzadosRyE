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
    if (isset($_POST['user_id'], $_POST['nombres'], $_POST['apellidos'], $_POST['email'], $_POST['telefono'], $_POST['descuento'])) {
        $user_id = $_POST['user_id'];
        $nombres = mysqli_real_escape_string($conn, $_POST['nombres']);
        $apellidos = mysqli_real_escape_string($conn, $_POST['apellidos']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $telefono = mysqli_real_escape_string($conn, $_POST['telefono']);
        $descuento = (float)$_POST['descuento'];  // Sanitize the descuento input

        // Actualizar usuario
        $sql = "UPDATE usuario SET Nombres='$nombres', Apellidos='$apellidos', Email='$email', Telefono='$telefono', Descuento='$descuento' WHERE IdUsuario='$user_id'";

        if (mysqli_query($conn, $sql)) {
            echo "<p>Usuario actualizado exitosamente.</p>";
        } else {
            echo "<p>Error al actualizar el usuario: " . mysqli_error($conn) . "</p>";
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

// Consulta para obtener todos los usuarios
$sql = "SELECT * FROM usuario";
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
                <input type="hidden" name="user_id" value="<?php echo $user['IdUsuario']; ?>">

                <label>Nombre:</label><br>
                <input type="text" name="nombres" required maxlength="20" value="<?php echo $user['Nombres']; ?>" required><br><br>

                <label>Apellido:</label><br>
                <input type="text" name="apellidos" required maxlength="20" value="<?php echo $user['Apellidos']; ?>" required><br><br>

                <label>Email:</label><br>
                <input type="email" name="email" value="<?php echo $user['Email']; ?>" required><br><br>

                <label>Teléfono:</label><br>
                <input type="text" name="telefono" required maxlength="8" value="<?php echo $user['Telefono']; ?>" required><br><br>

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
        <h2>Lista de usuarios</h2>
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
