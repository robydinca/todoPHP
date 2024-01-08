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

$note_id = isset($_GET['note_id']) ? $_GET['note_id'] : '';

// Verificar si se proporcionó un ID de nota válido
if (empty($note_id)) {
    echo "ID de nota no válido.";
    exit();
}

$conexion = new mysqli(HOST, USER, PASSWORD, DB, PORT);

if ($conexion->connect_error) {
    die("Error en la conexión: " . $conexion->connect_error);
}

$notes = new Notes($conexion);

// Obtener la información de la nota específica del usuario actual
$note = $notes->getNoteByIdAndUser($note_id, $security->getCurrentUserLogin());

if (!$note) {
    echo "No se encontró la nota o no tienes permisos para editarla.";
    exit();
}

// Si se ha enviado el formulario de edición, procesa la actualización de la nota
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $content = isset($_POST['content']) ? $_POST['content'] : '';
    $date = isset($_POST['date']) ? $_POST['date'] : '';
    $status = isset($_POST['status']) ? $_POST['status'] : '';

    // Verificar que el valor de status sea uno de los permitidos
    $allowed_statuses = array('pending', 'in progress', 'completed');
    if (!in_array($status, $allowed_statuses)) {
        echo "Valor de estado no válido.";
        exit();
    }

    // Actualizar la nota sin incluir task_id y course_id
    $updateResult = $notes->updateNoteData([
        'note_id' => $note_id,
        'title' => $title,
        'content' => $content,
        'date' => $date,
        'status' => $status
    ]);

    if ($updateResult) {
        echo "Nota actualizada exitosamente.";
    } else {
        echo "Error al actualizar la nota.";
    }

    $conexion->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Nota</title>
</head>
<body>
<?php if ($security->isAuth() && $note) : ?>
    <h2>Editar Nota</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?note_id=<?php echo $note_id; ?>" method="POST">
        <!-- Tu código de formulario -->
        <label for="title">Título:</label><br>
        <input type="text" id="title" name="title" value="<?php echo $note['title']; ?>" required><br><br>

        <label for="content">Contenido:</label><br>
        <textarea id="content" name="content" rows="10" cols="50" required><?php echo $note['content']; ?></textarea><br><br>

        <label for="date">Fecha:</label><br>
        <input type="date" id="date" name="date" value="<?php echo $note['date']; ?>" required><br><br>

        <label for="status">Estado:</label><br>
        <select id="status" name="status">
            <option value="pending" <?php echo $note['status'] === 'pending' ? 'selected' : ''; ?>>Pendiente</option>
            <option value="in progress" <?php echo $note['status'] === 'in progress' ? 'selected' : ''; ?>>En progreso</option>
            <option value="completed" <?php echo $note['status'] === 'completed' ? 'selected' : ''; ?>>Completado</option>
        </select><br><br>

        <input type="submit" value="Guardar cambios">
    </form>
<?php endif; ?>
</body>
</html>
