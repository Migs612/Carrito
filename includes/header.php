<?php
session_start();
$_SESSION['carrito'] = $_SESSION['carrito'] ?? [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda Online</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@glidejs/glide/dist/css/glide.core.min.css">
    <link rel="stylesheet" href="css/custom.css">
    <meta name="color-scheme" content="light">
</head>
<body class="bg-gray-50 text-gray-900">

<nav class="bg-white shadow">
  <div class="max-w-7xl mx-auto px-4">
    <div class="flex justify-between h-16 items-center">
      <a href="index.php" class="flex items-center space-x-3">
        <span class="text-2xl font-bold text-blue-800">MiTienda</span>
      </a>
      <div class="hidden md:flex items-center space-x-6">
        <a href="index.php" class="text-gray-700 hover:text-blue-600">Inicio</a>
        <a href="carrito.php" class="text-gray-700 hover:text-blue-600">Carrito (<?php echo count($_SESSION['carrito']); ?>)</a>
        <?php if (isset($_SESSION['id_usuario'])): ?>
           <a href="perfil.php" class="text-gray-700 hover:text-blue-600">Mi Perfil</a>
           <a href="logout.php" class="text-gray-700 hover:text-blue-600">Cerrar Sesión</a>
        <?php else: ?>
           <a href="login.php" class="text-gray-700 hover:text-blue-600">Login</a>
           <a href="registro.php" class="text-gray-700 hover:text-blue-600">Registro</a>
        <?php endif; ?>
      </div>
      <div class="md:hidden">
         <button id="menu-btn" class="text-gray-700">Menu</button>
      </div>
    </div>
  </div>
</nav>
