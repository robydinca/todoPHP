<?php
require_once "../config/config.php";

$conexion = new mysqli(HOST, USER, PASSWORD, DB, PORT);

if ($conexion->connect_errno) {
    die("Error de conexión: " . $conexion->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $login = $_POST['login'];
    $name = $_POST['name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    // Actualizar la información del usuario en la base de datos
    $consulta = "UPDATE users SET name=?, last_name=?, email=?, role=? WHERE login=?";
    $stmt = $conexion->prepare($consulta);
    $stmt->bind_param("sssss", $name, $last_name, $email, $role, $login);
    
    if ($stmt->execute()) {
        // La actualización fue exitosa
        header("Location: edit_user.php?id=$login");
        exit();
    } else {
        echo "Error al actualizar el usuario: " . $conexion->error;
    }
} else {
    echo "Acceso no válido.";
}
?>
