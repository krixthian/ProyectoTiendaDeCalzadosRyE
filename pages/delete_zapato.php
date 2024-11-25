<?php
include '../includes/db.php'; // Conexión a la base de datos

// Verificar si se ha enviado el ID del zapato
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_zapato'])) {
    $id_zapato = intval($_POST['id_zapato']); // Asegurarse de que sea un número entero

    // Consulta para eliminar el zapato
    $sql = "DELETE FROM zapato WHERE IdZapato = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id_zapato);

    if (mysqli_stmt_execute($stmt)) {
        // Redirigir con un mensaje de éxito
        header('Location: inventory.php?mensaje=eliminado');
    } else {
        // Redirigir con un mensaje de error
        header('Location: inventory.php?mensaje=error');
    }

    mysqli_stmt_close($stmt);
} else {
    // Redirigir si se accede de forma incorrecta
    header('Location: inventory.php');
}

mysqli_close($conn);
?>
