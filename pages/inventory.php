<?php
include_once '../app.php'; 
if (!isAdmin()) {
    header('Location: login.php');
    exit();
}

include '../includes/db.php'; // Conexión a la base de datos

// Verificar si se ha enviado el formulario de búsqueda
$search_query = "";
if (isset($_POST['search_name'])) {
    $search_name = mysqli_real_escape_string($conn, $_POST['search_name']);
    $search_query = " WHERE Nombre LIKE '%$search_name%'";
}

// Consulta para obtener todos los calzados o los calzados filtrados por nombre
$sql = "SELECT * FROM zapato" . $search_query;
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Inventario de Calzados</title>
    <style>
        .container { width: 80%; margin: 0 auto; padding: 20px; }
        .card-container { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; }
        .card { background-color: #f9f9f9; padding: 20px; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: transform 0.3s ease; }
        .card:hover { transform: translateY(-5px); }
        .card img { max-width: 100%; border-radius: 8px; }
        .card h3 { margin-bottom: 10px; font-size: 1.2rem; font-weight: bold; }
        .card p { font-size: 1rem; margin-bottom: 10px; }
        .price { font-weight: bold; color: green; }
        .discount { color: red; font-weight: bold; }
        .search-form input { padding: 8px; width: 250px; margin-right: 10px; }
        .search-form button { padding: 8px 15px; background-color: #4CAF50; color: white; border: none; cursor: pointer; }
        .update-button { display: inline-block; margin-top: 10px; padding: 8px 15px; background-color: #007BFF; color: white; text-decoration: none; border-radius: 5px; text-align: center; }
        .update-button:hover { background-color: #0056b3; }
        .delete-button { padding: 8px 15px; background-color: #FF4D4D; color: white; border: none; border-radius: 5px; cursor: pointer; }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <div class="container">
        <h2>Inventario de Calzados</h2>

        <!-- Mensajes -->
        <?php if (isset($_GET['mensaje'])): ?>
            <div class="alert">
                <?php if ($_GET['mensaje'] === 'eliminado'): ?>
                    <p style="color: green;">Zapato eliminado correctamente.</p>
                <?php elseif ($_GET['mensaje'] === 'error'): ?>
                    <p style="color: red;">Hubo un error al eliminar el zapato.</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Formulario de búsqueda -->
        <div class="search-form">
            <form method="POST">
                <input type="text" name="search_name" placeholder="Buscar por nombre del calzado" value="<?php echo isset($search_name) ? $search_name : ''; ?>" />
                <button type="submit">Buscar</button>
            </form>
        </div>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="card-container">
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="card">
                        <h3><?php echo $row['Nombre']; ?> - Talla <?php echo $row['Talla']; ?></h3>
                        <img src="../uploads/<?php echo $row['FotoZapato']; ?>" alt="<?php echo $row['Nombre']; ?>">
                        <p><strong>Descripción:</strong> <?php echo $row['Descripcion']; ?></p>
                        <p><strong>Stock:</strong> <?php echo $row['Stock']; ?></p>
                        <p class="price"><strong>Precio:</strong> Bs <?php echo number_format($row['Precio'], 2); ?></p>
                        <?php if ($row['Descuento'] > 0): ?>
                            <p class="discount"><strong>Descuento:</strong> <?php echo $row['Descuento']; ?>%</p>
                        <?php endif; ?>

                        <!-- Botón Actualizar -->
                        <a href="editar_zapato.php?id=<?php echo $row['IdZapato']; ?>" class="update-button">Actualizar</a>

                        <!-- Botón Eliminar con confirmación -->
                        <form action="delete_zapato.php" method="POST" style="margin-top: 10px;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este zapato?');">
                            <input type="hidden" name="id_zapato" value="<?php echo $row['IdZapato']; ?>">
                            <button type="submit" class="delete-button">Eliminar</button>
                        </form>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>No se encontraron calzados que coincidan con tu búsqueda.</p>
        <?php endif; ?>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
