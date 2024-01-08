<?php
session_start(); // Inicia la sesión

// Verifica si el usuario está autenticado
if (!isset($_SESSION['user_login'])) {
  header("Location: login.php");
  exit();
}

require_once "../config/config.php";

$conexion = new mysqli(HOST, USER, PASSWORD, DB, PORT);

if ($conexion->connect_errno) {
  die("Error de conexión: " . $conexion->connect_error);
}

$user_login = $_SESSION['user_login'];

$consulta = "SELECT * FROM notes WHERE user_login = ?";
$stmt = $conexion->prepare($consulta);
$stmt->bind_param("s", $user_login);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
  die("Error al ejecutar la consulta: " . $conexion->error);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Notas del Usuario</title>
  <link rel="stylesheet" href="../assets/style.css">
  <link rel="stylesheet" href="../assets/dashboard.css">
</head>

<body>
  <div class="container">

    <section class="notas">
      <h1>Notas del Usuario</h1>

      <?php if ($result->num_rows > 0) : ?>
        <?php while ($row = $result->fetch_assoc()) : ?>
          <div class="nota">
            <h3>Título: <?php echo $row['title']; ?></h3>
            <p>Contenido: <?php echo $row['content']; ?></p>
            <p>Fecha: <?php echo $row['date']; ?></p>
            <p>Estado: <?php echo $row['status']; ?></p>
            <!-- Mostrar otros detalles de la nota según tu estructura de base de datos -->

            <!-- Botones para editar y eliminar notas -->
            <a href="../model/edit_note.php?note_id=<?php echo $row['note_id']; ?>" class="boton">Editar</a>
            <a href="../model/delete_note.php?note_id=<?php echo $row['note_id']; ?>" class="boton">Eliminar</a>
          </div>
        <?php endwhile; ?>
      <?php else : ?>
        <p>No se encontraron notas para este usuario.</p>
      <?php endif; ?>

      <!-- Botón para agregar una nueva nota -->
      <a href="../model/add_note.php" class="boton">Agregar Nueva Nota</a>
    </section>


      <?php
        require_once "chatGPT.php";
      ?>
    </section>
    
  </div>
</body>

</html>