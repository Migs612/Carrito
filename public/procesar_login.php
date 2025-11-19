<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_usuario = $_POST['nombre_usuario'];
    $contrasena = $_POST['contrasena'];

    $stmt = $db->prepare("SELECT id_usuario, contrasena FROM Usuarios WHERE nombre_usuario = ?");
    $stmt->bind_param("s", $nombre_usuario);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id_usuario, $hash_contrasena);
        $stmt->fetch();

        if ($contrasena === $hash_contrasena) {
            $_SESSION['id_usuario'] = $id_usuario;
            $_SESSION['nombre_usuario'] = $nombre_usuario;
            header("Location: index.php");
            exit();
        } else {
            echo "Contraseña incorrecta.";
        }
    } else {
        echo "Usuario no encontrado.";
    }

    $stmt->close();
    $db->close();
}
?>
