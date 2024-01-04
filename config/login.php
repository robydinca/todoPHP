<?php
require_once "config.php";
require_once "../controllers/Users.php"; 
require_once "../controllers/Security.php"; 
$conexion = new mysqli(HOST, USER, PASSWORD, DB, PORT);

if ($conexion->connect_errno) {
    die("Error de conexión: " . $conexion->connect_error);
}

$security = new Security(); // Instancia la clase Security

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login']) && isset($_POST['password'])) {
    $login = $_POST['login'];
    $password = $_POST['password'];

    $consulta = "SELECT * FROM users WHERE login = ?";
    $stmt = $conexion->prepare($consulta);
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $user = $resultado->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $security->login($login, $user['role']); // Utiliza el método login de la clase Security
            header("Location: ../index.php");
            exit;
        } else {
            echo "user o contraseña incorrectos";
        }
    } else {
        echo "user no encontrado";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Simple Dashboard</title>
  <link type="text/css" rel="stylesheet" href="./estilos/forms.css">
</head>

<body>
  <form action="" method="post">
    <h1>Login</h1>
    <input type="text" name="login" id="login" placeholder="user" required>
    <input type="password" name="password" id="password" placeholder="Contraseña" required>
    <input type="submit" value="Enviar" class="boton">
  </form>
</body>

</html>
