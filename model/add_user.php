<?php
require_once '../config/config.php';
require_once '../controllers/Users.php';
require_once '../controllers/Security.php'; 

$security = new Security();

if ($_SERVER["REQUEST_METHOD"] == "POST" && $security->hasPermission('admin')) {
    $conexion = new mysqli(HOST, USER, PASSWORD, DB, PORT);

    if ($conexion->connect_error) {
        die("Error en la conexión: " . $conexion->connect_error);
    }

    $name = $_POST['name'];
    $last_name = $_POST['last_name'];
    $login = $_POST['login'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $users = new Users($conexion);

    // Hash de la contraseña
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Generación de salt (considerando el campo 'salt' de la tabla)
    $salt = bin2hex(random_bytes(10)); // Cambiar el número de bytes según sea necesario

    // Definir valores predeterminados para los campos 'salt' y 'email'
    $salt = bin2hex(random_bytes(10));
    $email = "null"; // Puedes manejar este campo según sea necesario

    $userData = array(
        'name' => $name,
        'last_name' => $last_name,
        'login' => $login,
        'password' => $passwordHash, // Guardar el hash de la contraseña
        'role' => $role,
        'salt' => $salt, // Agregar 'salt' al array de datos
        'email' => $email // Agregar 'email' al array de datos
    );

    $insertResult = $users->insert($userData);

    if ($insertResult === TRUE) {
        echo "Usuario agregado exitosamente.";
    } else {
        echo "Error al agregar el usuario: " . $conexion->error;
    }

    $conexion->close();
} elseif (!$security->hasPermission('admin')) {
    echo "Acceso denegado. Debes tener permisos de administrador para acceder a esta página.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Agregar Usuario</title>
</head>
<body>
  <?php if ($security->hasPermission('admin')) : ?>
    <h2>Agregar Usuario</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
      <label for="name">Nombre:</label>
      <input type="text" id="name" name="name" required><br><br>

      <label for="last_name">Apellido:</label>
      <input type="text" id="last_name" name="last_name" required><br><br>

      <label for="login">Nombre de usuario:</label>
      <input type="text" id="login" name="login" required><br><br>

      <label for="password">Contraseña:</label>
      <input type="password" id="password" name="password" required><br><br>

      <label for="role">Rol:</label>
      <input type="text" id="role" name="role"><br><br>

      <input type="submit" value="Agregar Usuario">
    </form>
  <?php endif; ?>
</body>
</html>
