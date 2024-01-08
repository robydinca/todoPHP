<?php
require_once "../config/config.php";
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
            $security->login($login, $user['role']);
            session_start();
            $_SESSION['user_login'] = $login; // Asigna el login a la variable de sesión user_login

            if ($user['role'] === 'user') {
                header("Location: ./dashboard.php"); // Redirige al dashboard de usuario normal
                exit;
            } elseif ($user['role'] === 'admin') {
                header("Location: ./users_panel.php"); // Redirige al panel de administrador
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,600&display=swap" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="../assets/login.css">
</head>

<body>
    <form action="" method="post">
        <h1>Iniciar Sesión</h1>
        <input type="text" name="login" id="login" placeholder="Usuario" required>
        <input type="password" name="password" id="password" placeholder="Contraseña" required>
        <input type="submit" value="Enviar" class="boton">
    </form>
</body>

</html>
