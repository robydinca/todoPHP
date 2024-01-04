<?php
require_once "config.php";

$conexion = new mysqli(HOST, USER, PASSWORD, DB, PORT);

if ($conexion->connect_errno) {
    die("Error de conexión: " . $conexion->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $apellidos = $_POST["apellidos"];
    $login = $_POST["login"];
    $password = $_POST["password"];
    $email = $_POST["email"]; // Nuevo campo agregado

    // Supongo que el rol siempre es 'admin' en este caso
    $rol = 'admin';

    // Hash de la contraseña
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Generación de salt (considerando el campo 'salt' de la tabla)
    $salt = bin2hex(random_bytes(10)); // Cambiar el número de bytes según sea necesario

    $consulta = "INSERT INTO users (name, last_name, salt, login, email, password, role) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($consulta);
    $stmt->bind_param("sssssss", $nombre, $apellidos, $salt, $login, $email, $passwordHash, $rol);

    if ($stmt->execute()) {
        echo "user creado correctamente<br>";
    } else {
        echo "Error al crear el user: " . $conexion->error . "<br>";
    }

    $stmt->close();
    $conexion->close();

    header("Location: ../index.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Users</title>
    <link type="text/css" rel="stylesheet" href="./estilos/forms.css">
</head>

<body>
    <form action="" method="post">
        <h1>Regístrate</h1>
        <input type="text" name="nombre" id="nombre" placeholder="Nombre" required>
        <input type="text" name="apellidos" id="apellidos" placeholder="Apellidos" required>
        <input type="text" name="login" id="login" placeholder="user" required>
        <input type="email" name="email" id="email" placeholder="Correo electrónico" required> <!-- Nuevo campo agregado -->
        <input type="password" name="password" id="password" placeholder="Contraseña" required>
        <input type="submit" value="Enviar" class="boton">
    </form>
</body>

</html>
