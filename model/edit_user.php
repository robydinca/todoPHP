<?php
require_once "../config/config.php";
require_once "../controllers/Security.php"; 

$security = new Security(); 

$conexion = new mysqli(HOST, USER, PASSWORD, DB, PORT);

if ($conexion->connect_errno) {
    die("Error de conexión: " . $conexion->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id']) && $security->hasPermission('admin')) {
    $login = $_GET['id'];
    
    // Obtener la información del usuario seleccionado por su ID
    $consulta = "SELECT * FROM users WHERE login = ?";
    $stmt = $conexion->prepare($consulta);
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();

?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Editar Usuario</title>
        </head>
        <body>
            <h2>Editar Usuario</h2>
            <form action="update_user.php" method="post">
                <input type="hidden" name="login" value="<?php echo $usuario['login']; ?>">
                Nombre: <input type="text" name="name" value="<?php echo $usuario['name']; ?>"><br><br>
                Apellido: <input type="text" name="last_name" value="<?php echo $usuario['last_name']; ?>"><br><br>
                Email: <input type="text" name="email" value="<?php echo $usuario['email']; ?>"><br><br>
                Rol: <input type="text" name="role" value="<?php echo $usuario['role']; ?>"><br><br>
                <input type="submit" value="Guardar cambios">
            </form>
        </body>
        </html>
<?php

    } else {
        echo "No se encontró ningún usuario con el ID proporcionado.";
    }
} elseif (!$security->hasPermission('admin')) {
    echo "Acceso denegado. Debes tener permisos de administrador para acceder a esta página.";
} else {
    echo "ID de usuario no proporcionado.";
}
?>
