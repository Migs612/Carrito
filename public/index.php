<?php include '../includes/header.php'; ?>

<div class="container">
    <h1>Bienvenido a la Tienda</h1>
    <p>Esta es la página principal de nuestro e-commerce.</p>
    
    <?php if (isset($_SESSION['id_usuario'])): ?>
        <p>Hola, <?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?>!</p>
        <a href="logout.php" class="btn btn-danger">Cerrar Sesión</a>
    <?php else: ?>
        <a href="login.php" class="btn btn-primary">Iniciar Sesión</a>
        <a href="registro.php" class="btn btn-secondary">Registrarse</a>
    <?php endif; ?>

</div>

<?php include '../includes/footer.php'; ?>
