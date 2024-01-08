<?php
require_once "../config/config.php";
require_once "../controllers/Security.php"; // Asegúrate de incluir la clase Security

$security = new Security(); // Instancia de la clase Security

$conexion = new mysqli(HOST, USER, PASSWORD, DB, PORT);

if ($conexion->connect_errno) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Verifica si se ha enviado un parámetro 'note_id' por GET
if (isset($_GET['note_id']) && !empty($_GET['note_id'])) {
    // Obtén el ID de la nota a eliminar
    $note_id = $_GET['note_id'];

    // Verifica si el usuario tiene permisos para eliminar la nota
    if ($security->hasPermission('admin') || $security->hasPermission('user')) {
        // Prepara la consulta para eliminar la nota
        $consulta = "DELETE FROM notes WHERE note_id = ?";
        $stmt = $conexion->prepare($consulta);

        if ($stmt) {
            // Vincula el parámetro a la declaración preparada como un entero
            $stmt->bind_param("i", $note_id);

            // Ejecuta la declaración preparada
            if ($stmt->execute()) {
                echo "Nota eliminada correctamente.";
            } else {
                echo "Error al eliminar la nota: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Error en la preparación de la consulta: " . $conexion->error;
        }
    } else {
        echo "Acceso denegado. No tienes permisos para eliminar esta nota.";
    }
} else {
    echo "ID de la nota no proporcionado.";
}

$conexion->close();
?>
