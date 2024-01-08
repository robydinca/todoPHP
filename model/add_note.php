<?php
require_once '../config/config.php';
require_once '../controllers/Security.php';
require_once '../controllers/Notes.php';

$security = new Security();

// Verifica si el usuario está logueado
if (!$security->isAuth()) {
    // Redirecciona al usuario a la página de inicio de sesión si no está logueado
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conexion = new mysqli(HOST, USER, PASSWORD, DB, PORT);

    if ($conexion->connect_error) {
        die("Error en la conexión: " . $conexion->connect_error);
    }

    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $content = isset($_POST['content']) ? $_POST['content'] : '';
    $date = isset($_POST['date']) ? $_POST['date'] : '';
    $status = isset($_POST['status']) ? $_POST['status'] : '';
    $task_id = isset($_POST['task_id']) ? $_POST['task_id'] : null;
    $course_id = isset($_POST['course_id']) ? $_POST['course_id'] : null;
    $menu_id = isset($_POST['menu_id']) ? $_POST['menu_id'] : null;
    $routine_id = isset($_POST['routine_id']) ? $_POST['routine_id'] : null;
    $user_login = $security->getCurrentUserLogin(); // Obtener el nombre de usuario del usuario actualmente logueado

    $notes = new Notes($conexion);

    $noteData = array(
        'title' => $title,
        'content' => $content,
        'date' => $date,
        'status' => $status,
        'task_id' => $task_id,
        'course_id' => $course_id,
        'menu_id' => $menu_id,
        'routine_id' => $routine_id,
        'user_login' => $user_login
    );

    $insertResult = $notes->insert($noteData);

    if ($insertResult === TRUE) {
        echo "Nota agregada exitosamente.";
    } else {
        echo "Error al agregar la nota: " . $conexion->error;
    }

    $conexion->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Agregar Nota</title>
  <link type="text/css" rel="stylesheet" href="../assets/style.css">
</head>
<body>
  <section class="addNote">

    <?php if ($security->isAuth()) : ?>
      <h2>Agregar Nota</h2>
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <label for="title">Título:</label>
        <input type="text" id="title" name="title" required><br><br>
  
        <label for="content">Contenido:</label>
        <textarea id="content" name="content" required></textarea><br><br>
  
        <label for="date">Fecha:</label>
        <input type="date" id="date" name="date" required><br><br>
  
        <label for="status">Estado:</label>
        <select id="status" name="status">
          <option value="pending">Pendiente</option>
          <option value="in progress">En progreso</option>
          <option value="completed">Completado</option>
        </select><br><br>
  
        <!-- Agrega aquí los campos adicionales de tu tabla 'notes' según sea necesario -->
        <!-- Recuerda que task_id, course_id, menu_id y routine_id pueden ser nulos -->
  
        <input type="submit" value="Agregar Nota">
      </form>
    <?php endif; ?>
  </section>
  <section class="chatbot">
    <?php
      require_once "../views/chatGPT.php";
    ?>
  </section>
</body>
</html>
