<?php
include_once '../app.php'; // Asegúrate de que la función isAdmin() esté incluida
include '../includes/db.php'; // Asegúrate de que la conexión a la base de datos esté configurada aquí

// Consulta para obtener solo los zapatos formales (Tipo 2)
$sql = "SELECT * FROM zapato WHERE Tipo = 2"; // Tipo 2 corresponde a zapatos formales
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Zapatos Formales - Tienda RyE</title>
    <style>
        /* Estilos generales para el contenedor */
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    text-align: center;
}

/* Titulo principal */
h2 {
    font-size: 2.5rem;
    color: #333;
    margin-bottom: 20px;
}

/* Estilo para la cuadrícula de zapatos (zapatos distribuidos en filas) */
.shoe-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); /* Ajuste automático de columnas */
    gap: 20px; /* Espacio entre los elementos de la cuadrícula */
    justify-items: center; /* Centra las tarjetas dentro de cada celda */
    margin-top: 20px;
}

/* Estilo para cada tarjeta de zapato */
.shoe-card {
    display: flex;
    flex-direction: column; /* Asegura que la imagen esté encima de los detalles del zapato */
    align-items: center; /* Centra los contenidos dentro de la tarjeta */
    background-color: #f9f9f9;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease; /* Para un efecto visual cuando el ratón pasa por encima */
    max-width: 280px;
    width: 100%;
    text-align: left;
}

.shoe-card:hover {
    transform: scale(1.05); /* Efecto de aumento al pasar el ratón por encima */
}

/* Estilo de la información del zapato */
.shoe-info {
    text-align: left;
    margin-bottom: 20px;
}

/* Estilo de la imagen */
.shoe-image img {
    width: 100%;
    height: auto;
    border-radius: 10px;
}

    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="container">
        <h2>Zapatos Formales Disponibles</h2>

        <?php
        // Verificar si hay zapatos formales en la base de datos
        if (mysqli_num_rows($result) > 0) {
            echo '<div class="shoe-grid">';  // Inicia la cuadrícula de zapatos
            // Mostrar los zapatos formales
            while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <div class="shoe-card">
                    <div class="shoe-info">
                        <h3 class="shoe-name"><?php echo $row['Nombre']; ?></h3>
                        <p><strong>Talla:</strong> <?php echo $row['Talla']; ?></p>
                        <p><strong>Descripción:</strong> <?php echo $row['Descripcion']; ?></p>
                        <p><strong>Precio:</strong> $<?php echo number_format($row['Precio'], 2); ?></p>
                        <p><strong>Descuento:</strong> <?php echo $row['Descuento']; ?>%</p>
                        <p><strong>Stock:</strong> <?php echo $row['Stock']; ?></p>
                    </div>
                    <div class="shoe-image">
                        <img src="../uploads/<?php echo $row['FotoZapato']; ?>" alt="Imagen del zapato" class="shoe-photo">
                    </div>
                </div>
                <?php
            }
            echo '</div>';  // Cierra la cuadrícula de zapatos
        } else {
            echo "<p>No hay zapatos formales disponibles actualmente.</p>";
        }
        ?>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
