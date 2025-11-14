<?php include '../includes/header.php'; ?>

<div class="container">
    <h2>Login</h2>
    <form action="procesar_login.php" method="post">
        <div class="form-group">
            <label for="nombre_usuario">Usuario</label>
            <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario" required>
        </div>
        <div class="form-group">
            <label for="contrasena">Contraseña</label>
            <input type="password" class="form-control" id="contrasena" name="contrasena" required>
        </div>
        <button type="submit" class="btn btn-primary">Entrar</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
