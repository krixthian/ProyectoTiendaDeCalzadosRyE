<?php
include_once '../app.php'; // Asegúrate de que la función isAdmin() esté incluida
include '../includes/db.php'; // Asegúrate de que la conexión a la base de datos esté configurada aquí

// Consulta para obtener los zapatos con descuento
$sql = "SELECT * FROM zapato WHERE Descuento > 0"; // Solo zapatos con descuento
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Zapatos en Promoción - Tienda RyE</title>
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

.shoe-name {
    font-size: 1.8rem;
    color: #333;
    font-weight: bold;
    margin-bottom: 10px;
}

.shoe-info p {
    font-size: 1rem;
    color: #555;
    margin-bottom: 10px;
}

/* Estilos para la imagen del zapato */
.shoe-image {
    text-align: center;
    padding-top: 20px;
}

.shoe-photo {
    width: 100%; /* Asegura que la imagen cubra todo el contenedor sin deformarse */
    height: auto;
    max-height: 300px;
    object-fit: cover;
    border-radius: 10px;
}

/* Aseguramos que las imágenes no distorsionen */
.shoe-image img {
    border-radius: 10px;
}

/* Si no hay zapatos, se muestra el mensaje */
.container p {
    font-size: 1.2rem;
    color: #777;
    margin-top: 20px;
}

    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="container">
        <h2>Zapatos en Promoción</h2>
        
        <?php
        // Verificar si hay zapatos con descuento en la base de datos
        if (mysqli_num_rows($result) > 0) {
            // Mostrar los zapatos con descuento dentro de un div contenedor
            echo '<div class="shoe-grid">';
            while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <div class="shoe-card">
    <div class="shoe-info">
        <h3 class="shoe-name"><?php echo $row['Nombre']; ?></h3>
        <p><strong>Talla:</strong> <?php echo $row['Talla']; ?></p>
        <p><strong>Descripción:</strong> <?php echo $row['Descripcion']; ?></p>
        <p><strong>Precio:</strong> Bs.<?php echo number_format($row['Precio'], 2); ?></p>
        <p><strong>Descuento:</strong> <?php echo $row['Descuento']; ?>%</p>
        <p><strong>Stock:</strong> <?php echo $row['Stock']; ?></p>
    </div>
    <div class="shoe-image">
        <img src="../uploads/<?php echo $row['FotoZapato']; ?>" alt="Imagen del zapato" class="shoe-photo">
    </div>
    <!-- Botón para WhatsApp -->
    <div class="shoe-whatsapp">
        <?php
        // Mensaje personalizado para WhatsApp
        $message = urlencode("¡Hola! Estoy interesado en el zapato " . $row['Nombre'] . 
                             " (Talla: " . $row['Talla'] . 
                             ", Precio: Bs." . number_format($row['Precio'], 2) . 
                             ", Descuento: " . $row['Descuento'] . "%).");
        // Número de WhatsApp al que se enviará el mensaje
        $whatsapp_number = "59176713767"; // Cambia esto por el número de tu tienda
        ?>
        <a href="https://wa.me/<?php echo $whatsapp_number; ?>?text=<?php echo $message; ?>" 
           target="_blank" 
           class="whatsapp-button" 
           style="display: inline-block; margin-top: 10px; padding: 10px 15px; background-color: #25D366; color: white; text-decoration: none; border-radius: 5px;">
            Consultar en WhatsApp
        </a>
    </div>
</div>

                <?php
            }
            echo '</div>';
        } else {
            // Si no hay zapatos en promoción
            echo "<p>No hay zapatos en promoción actualmente.</p>";
        }
        ?>
        
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
