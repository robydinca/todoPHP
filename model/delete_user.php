<?php
require_once "../config/config.php";
require_once "../controllers/Users.php";
require_once "../controllers/Security.php"; // Asegúrate de incluir la clase Security

$security = new Security(); // Instancia de la clase Security

$conexion = new mysqli(HOST, USER, PASSWORD, DB, PORT);

if ($conexion->connect_errno) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Verifica si se ha enviado un parámetro 'id' por GET
if (isset($_GET['id']) && !empty($_GET['id']) && $security->hasPermission('admin')) {
    // Obtén el ID del usuario a eliminar
    $user_id = $_GET['id'];

    // Crea una instancia de la clase Users
    $users = new Users($conexion);

    // Intenta eliminar al usuario con el ID proporcionado
    $delete_result = $users->delete($user_id);

    if ($delete_result) {
        echo "Usuario eliminado correctamente.";
    } else {
        echo "Error al eliminar el usuario.";
    }
} elseif (!$security->hasPermission('admin')) {
    echo "Acceso denegado. Debes tener permisos de administrador para realizar esta acción.";
} else {
    echo "ID de usuario no proporcionado.";
}

$conexion->close();
?>
