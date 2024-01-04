<?php
require_once "../config/config.php";

$conexion = new mysqli(HOST, USER, PASSWORD, DB, PORT);

if ($conexion->connect_errno) {
    die("Error de conexión: " . $conexion->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $login = $_GET['id'];
    
    // Obtener la información del usuario seleccionado por su ID
    $consulta = "SELECT * FROM users WHERE login = ?";
    $stmt = $conexion->prepare($consulta);
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();
        // Aquí empieza el formulario para editar el usuario
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
        // Aquí termina el formulario para editar el usuario
    } else {
        echo "No se encontró ningún usuario con el ID proporcionado.";
    }
} else {
    echo "ID de usuario no proporcionado.";
}
?>
