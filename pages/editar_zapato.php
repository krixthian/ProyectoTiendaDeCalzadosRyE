<?php
include_once '../app.php'; 
if (!isAdmin()) {
    header('Location: login.php');
    exit();
}

include '../includes/db.php'; 

// Verificar si se ha proporcionado un ID válido
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "ID no válido.";
    exit();
}

$id = intval($_GET['id']); // Sanitizar el ID

// Obtener los datos del zapato desde la base de datos
$sql = "SELECT * FROM zapato WHERE IdZapato = $id";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    echo "Zapato no encontrado.";
    exit();
}

$zapato = mysqli_fetch_assoc($result);

// Procesar la actualización al enviar el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $talla = mysqli_real_escape_string($conn, $_POST['talla']);
    $descripcion = mysqli_real_escape_string($conn, $_POST['descripcion']);
    $precio = floatval($_POST['precio']);
    $descuento = intval($_POST['descuento']);
    $stock = intval($_POST['stock']);

    // Actualizar en la base de datos
    $update_sql = "UPDATE zapato 
                   SET Nombre = '$nombre', Talla = '$talla', Descripcion = '$descripcion', 
                       Precio = $precio, Descuento = $descuento, Stock = $stock
                   WHERE IdZapato = $id";

    if (mysqli_query($conn, $update_sql)) {
        echo "<script>alert('Zapato actualizado exitosamente'); window.location.href='inventory.php';</script>";
    } else {
        echo "Error al actualizar el zapato: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Editar Zapato</title>
    <style>
        
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-bottom: 5px;
            font-weight: bold;
        }
        input, textarea, select, button {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="container">
        <h2>Editar Zapato</h2>
        <form method="POST">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($zapato['Nombre']); ?>" required>

            <label for="talla">Talla:</label>
            <input type="text" id="talla" name="talla" value="<?php echo htmlspecialchars($zapato['Talla']); ?>" required>

            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion" rows="4" required><?php echo htmlspecialchars($zapato['Descripcion']); ?></textarea>

            <label for="precio">Precio (Bs):</label>
            <input type="number" step="0.01" id="precio" name="precio" value="<?php echo $zapato['Precio']; ?>" required>

            <label for="descuento">Descuento (%):</label>
            <input type="number" id="descuento" name="descuento" value="<?php echo $zapato['Descuento']; ?>">

            <label for="stock">Stock:</label>
            <input type="number" id="stock" name="stock" value="<?php echo $zapato['Stock']; ?>" required>

          
            <button type="submit">Guardar Cambios</button>
        </form>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
