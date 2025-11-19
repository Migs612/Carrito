<?php include '../includes/header.php'; ?>

<div class="max-w-md mx-auto mt-12 bg-white p-8 rounded-lg shadow">
    <h2 class="text-2xl font-semibold mb-6">Iniciar sesión</h2>
    <form action="procesar_login.php" method="post" class="space-y-4">
        <div>
            <label for="nombre_usuario" class="block text-sm font-medium text-gray-700">Usuario</label>
            <input type="text" id="nombre_usuario" name="nombre_usuario" required class="mt-1 block w-full border-gray-200 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div>
            <label for="contrasena" class="block text-sm font-medium text-gray-700">Contraseña</label>
            <input type="password" id="contrasena" name="contrasena" required class="mt-1 block w-full border-gray-200 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div>
            <button type="submit" class="w-full bg-blue-800 text-white py-2 rounded-md">Entrar</button>
        </div>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
