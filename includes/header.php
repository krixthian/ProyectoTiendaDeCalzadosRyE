<div class="header">
    <div class="container">
        <h1>RyE - Tienda de Calzados</h1>
        <nav>
            <a href="index.php">Inicio</a>
            <?php
// Función para verificar si es administrador

?>



    <?php if (isAdmin()): ?>
        <!-- Si el usuario es admin, muestra las opciones de admin -->
        <a href="inventory.php">Inventario</a>
        <a href="add-shoes.php">Agregar Calzado</a>
        <a href="agregarusuario.php">Agregar Empleado</a>
        <a href="adminusuario.php">Administrar usuarios</a>
    <?php endif; ?>


            <!-- Si el usuario está logueado, muestra las opciones para ellos -->
            <?php if (isUserLoggedIn()): ?>
                <a href="promociones.php">Promociones</a>
                <a href="deportivos.php">Calzados Deportivos</a>
                <a href="traje.php">Calzados para Traje</a>
                <a href="todos.php">Todos los calzados</a>
                <a href="logout.php">Cerrar Sesión</a>
            <?php else: ?>
                <!-- Si no hay usuario logueado, muestra solo login -->
                <a href="login.php">Login</a>
            <?php endif; ?>
        </nav>

        <!-- Información del usuario logueado -->
        <?php if (isUserLoggedIn()): ?>
            <div class="user-info">
                <p>Bienvenido, <strong><?= htmlspecialchars($_SESSION['user']) ?></strong></p>
            </div>
        <?php endif; ?>
    </div>
</div>
