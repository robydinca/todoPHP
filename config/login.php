<?php
/**
 * Este archivo maneja el inicio de sesión de los usuarios.
 * Verifica las credenciales proporcionadas y redirige según el rol del usuario.
 */

// Incluir los archivos necesarios
require_once "config.php";
require_once "../controllers/Users.php"; 
require_once "../controllers/Security.php"; 

// Establecer conexión a la base de datos
$conexion = new mysqli(HOST, USER, PASSWORD, DB, PORT);

// Verificar si hay error en la conexión
if ($conexion->connect_errno) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Crear una instancia de la clase Security
$security = new Security();

// Verificar si el formulario se envió mediante el método POST y si se proporcionaron datos de inicio de sesión
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login']) && isset($_POST['password'])) {
    $login = $_POST['login'];
    $password = $_POST['password'];

    // Consultar en la base de datos el usuario con el login proporcionado
    $consulta = "SELECT * FROM users WHERE login = ?";
    $stmt = $conexion->prepare($consulta);
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $resultado = $stmt->get_result();

    // Verificar si se encontró un usuario con el login especificado
    if ($resultado->num_rows === 1) {
        $user = $resultado->fetch_assoc();

        // Verificar si la contraseña coincide con la almacenada en la base de datos
        if (password_verify($password, $user['password'])) {
            // Verificar el rol del usuario y redirigir a la página correspondiente
            if ($user['role'] === 'admin') {
                $security->login($login, $user['role']);
                header("Location: ../views/users_panel.php");
                exit;
            } else {
                $security->login($login, $user['role']);
                header("Location: ../index.php");
                exit;
            }
        } else {
            echo "Usuario o contraseña incorrectos";
        }
    } else {
        echo "Usuario no encontrado";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Encabezado del HTML -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Dashboard</title>
    <link type="text/css" rel="stylesheet" href="./estilos/forms.css">
    
</head>

<body>
    <!-- Formulario de inicio de sesión -->
    <form action="" method="post">
        <h1>Login</h1>
        <input type="text" name="login" id="login" placeholder="Usuario" required>
        <input type="password" name="password" id="password" placeholder="Contraseña" required>
        <input type="submit" value="Enviar" class="boton">
    </form>
</body>

</html>
