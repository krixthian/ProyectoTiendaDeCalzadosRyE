<?php
include_once '../app.php'; // Asegúrate de que la función isAdmin() esté incluida
if (!isAdmin()) {
    header('Location: login.php');
    exit();
}

include '../includes/db.php'; // Asegúrate de que la conexión a la base de datos esté configurada aquí

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitizar y obtener los datos del formulario
    $shoe_name = mysqli_real_escape_string($conn, $_POST['shoe_name']);
    $shoe_size = mysqli_real_escape_string($conn, $_POST['shoe_size']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $discount = mysqli_real_escape_string($conn, $_POST['discount']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $stock = mysqli_real_escape_string($conn, $_POST['stock']);
    $shoe_type = mysqli_real_escape_string($conn, $_POST['shoe_type']);

    // Subir la foto
    $photo_name = $_FILES['shoe_photo']['name'];
    $photo_tmp = $_FILES['shoe_photo']['tmp_name'];
    $photo_ext = pathinfo($photo_name, PATHINFO_EXTENSION);
    $photo_new_name = "shoe_" . uniqid() . "." . $photo_ext;
    $photo_path = "../uploads/" . $photo_new_name;

    // Mover la foto a la carpeta de destino
    if (move_uploaded_file($photo_tmp, $photo_path)) {
        // Insertar los datos en la base de datos
        $sql = "INSERT INTO zapato (Nombre, Talla, Descripcion, Precio, Stock, Descuento, Tipo, FotoZapato) 
                VALUES ('$shoe_name', '$shoe_size', '$description', '$price', '$stock', '$discount', '$shoe_type', '$photo_new_name')";

        if (mysqli_query($conn, $sql)) {
            echo "<p>Zapato agregado exitosamente.</p>";
        } else {
            echo "<p>Error al agregar el zapato: " . mysqli_error($conn) . "</p>";
        }
    } else {
        echo "<p>Error al subir la foto. Asegúrate de que el archivo sea válido.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Agregar Zapato - Tienda RyE</title>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="container">
        <h2>Agregar Nuevo Zapato</h2>
        <form method="POST" enctype="multipart/form-data">
            <label>Nombre del Zapato:</label><br>
            <input type="text" required maxlength="100" name="shoe_name" required><br><br>

            <label>Talla:</label><br>
            <input type="text" required maxlength="10" name="shoe_size" maxlength="5" required><br><br>

            <label>Descripción:</label><br>
            <textarea name="description" rows="4"></textarea><br><br>

            <label>Precio:</label><br>
            <input type="number" required maxlength="10" name="price" step="0.01" required><br><br>

            <label>Stock:</label><br>
            <input type="number" name="stock" required><br><br>

            <label>Descuento (%):</label><br>
            <input type="number" name="discount" step="0.01" value="0"><br><br>

            <label>Tipo de Calzado:</label><br>
            <select name="shoe_type" required>
                <option value="1">Deportivo</option>
                <option value="2">Formal</option>
                <option value="3">Casual</option>
            </select><br><br>

            <label>Foto del Zapato:</label><br>
            <input type="file" name="shoe_photo" accept="image/*" required><br><br>

            <button type="submit">Agregar</button>
        </form>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
