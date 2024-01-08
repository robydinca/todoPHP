<?php
// Iniciar la sesión si no está iniciada
session_start();

// Destruir toda la información relacionada con la sesión actual
session_destroy();

// Redirigir a la página de inicio o a otra página después de cerrar sesión
header("Location: ../index.php");
exit;
?>
