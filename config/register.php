<?php
// Se incluye el archivo de configuración
require_once "config.php";

// Se establece la conexión a la base de datos utilizando los valores definidos en el archivo de configuración
$conexion = new mysqli(HOST, USER, PASSWORD, DB, PORT);

// Verifica si hay errores en la conexión
if ($conexion->connect_errno) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Verifica si la solicitud es de tipo POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Se obtienen los datos del formulario
    $nombre = $_POST["nombre"];
    $apellidos = $_POST["apellidos"];
    $login = $_POST["login"];
    $password = $_POST["password"];
    $email = $_POST["email"]; // Nuevo campo agregado

    // Suposición: el rol siempre es 'admin' en este caso
    $rol = 'admin';

    // Hash de la contraseña usando el algoritmo PASSWORD_DEFAULT
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Generación de salt para mayor seguridad (considerando el campo 'salt' de la tabla)
    $salt = bin2hex(random_bytes(10)); // Puedes cambiar el número de bytes según sea necesario

    // Consulta preparada para insertar datos en la tabla 'users'
    $consulta = "INSERT INTO users (name, last_name, salt, login, email, password, role) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($consulta);

    // Se vinculan los parámetros con la consulta preparada
    $stmt->bind_param("sssssss", $nombre, $apellidos, $salt, $login, $email, $passwordHash, $rol);

    // Ejecución de la consulta preparada
    if ($stmt->execute()) {
        echo "Usuario creado correctamente<br>";
    } else {
        echo "Error al crear el usuario: " . $conexion->error . "<br>";
    }

    // Se cierra la consulta preparada y la conexión a la base de datos
    $stmt->close();
    $conexion->close();

    // Redireccionamiento a la página de inicio después de registrar al usuario
    header("Location: ../index.php");
}
?>

<!DOCTYPE html>
<html lang="es"> <!-- Se cambió 'en' por 'es' para español -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuarios</title> <!-- Se corrigió 'Users' a 'Usuarios' -->
    <link type="text/css" rel="stylesheet" href="./estilos/forms.css">
</head>
<body>
    <!-- Formulario de registro -->
    <form action="" method="post">
        <h1>Regístrate</h1>
        <!-- Campos del formulario -->
        <input type="text" name="nombre" id="nombre" placeholder="Nombre" required>
        <input type="text" name="apellidos" id="apellidos" placeholder="Apellidos" required>
        <input type="text" name="login" id="login" placeholder="Usuario" required> <!-- Se cambió 'user' a 'Usuario' -->
        <input type="email" name="email" id="email" placeholder="Correo electrónico" required> <!-- Nuevo campo agregado -->
        <input type="password" name="password" id="password" placeholder="Contraseña" required>
        <input type="submit" value="Enviar" class="boton">
    </form>
</body>
</html>
