<div class="header">
    <div class="container">
        <h1>RyE - Tienda de Calzados</h1>
        <nav>
            <a href="index.php">Inicio</a>

            <!-- Si el usuario es admin, muestra las opciones de admin -->
            <?php if (isAdmin()): ?>
                <a href="inventory.php">Inventario</a>
                <a href="add-shoes.php">Agregar Calzado</a>
                <a href="agregarusuario.php">Agregar usuario</a>
                <a href="adminusuario.php">Administrar usuarios</a>
            <?php endif; ?>

            <!-- Si el usuario está logueado, muestra las secciones para ellos -->
            <?php if (isUserLoggedIn()): ?>
                <a href="promociones.php">Promociones</a>
                <a href="deportivos.php">Calzados Deportivos</a>
                <a href="traje.php">Calzados para Traje</a>
                <a href="todos.php">todos los calzados</a>
                <a href="logout.php">Cerrar Sesión</a>
            <?php endif; ?>

            <!-- Si no hay usuario logueado, muestra solo login -->
            <?php if (!isUserLoggedIn()): ?>
                <a href="login.php">Login</a>
            <?php endif; ?>
        </nav>

        <?php if (isUserLoggedIn()): ?>
            <div class="user-info">
                <p>Bienvenido, <strong><?= $_SESSION['user'] ?></strong></p>
            </div>
        <?php endif; ?>
    </div>
</div>
