<?php
require_once "../config/config.php";

$conexion = new mysqli(HOST, USER, PASSWORD, DB, PORT);

if ($conexion->connect_errno) {
    die("Error de conexión: " . $conexion->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = $_POST["login"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Suponemos que el rol de usuario normal es 'user' en este caso
    $rol = 'user';

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $salt = bin2hex(random_bytes(10));

    $nombre = !empty($_POST["nombre"]) ? $_POST["nombre"] : NULL;
    $apellidos = !empty($_POST["apellidos"]) ? $_POST["apellidos"] : NULL;

    $consulta = "INSERT INTO users (name, last_name, salt, login, email, password, role) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($consulta);
    $stmt->bind_param("sssssss", $nombre, $apellidos, $salt, $login, $email, $passwordHash, $rol);

    if ($stmt->execute()) {
        echo "Usuario creado correctamente<br>";
    } else {
        echo "Error al crear el usuario: " . $conexion->error . "<br>";
    }

    $stmt->close();
    $conexion->close();

    header("Location: ./dashboard.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuarios</title>
    <link type="text/css" rel="stylesheet" href="../assets/login.css">

</head>

<body>
    <form action="" method="post">
        <h1>Registro de Usuarios</h1>
        <input type="text" name="nombre" id="nombre" placeholder="Nombre">
        <input type="text" name="apellidos" id="apellidos" placeholder="Apellidos">
        <input type="text" name="login" id="login" placeholder="Usuario" required>
        <input type="email" name="email" id="email" placeholder="Correo electrónico" required>
        <input type="password" name="password" id="password" placeholder="Contraseña" required>
        <input type="submit" value="Enviar" class="boton">
    </form>
</body>

</html>
