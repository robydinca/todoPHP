<?php
require_once "../config/config.php";
require_once "../model/edit_user.php";
$conexion = new mysqli(HOST, USER, PASSWORD, DB, PORT);

if ($conexion->connect_errno) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Obtener la lista de usuarios
$consulta = "SELECT * FROM users";
$resultado = $conexion->query($consulta);

// Mostrar la tabla de usuarios
echo "<h2>Lista de Usuarios</h2>";
echo "<table border='1'>";
echo "<tr><th>Nombre</th><th>Apellido</th><th>Login</th><th>Email</th><th>Rol</th><th>Acciones</th></tr>";

while ($fila = $resultado->fetch_assoc()) {
    echo "<tr>";
    echo "<td>{$fila['name']}</td>";
    echo "<td>{$fila['last_name']}</td>";
    echo "<td>{$fila['login']}</td>";
    echo "<td>{$fila['email']}</td>";
    echo "<td>{$fila['role']}</td>";
    echo "<td>
              <a href='../model/edit_user.php?id={$fila['login']}'>Editar</a> | 
              <a href='delete_user.php?id={$fila['login']}'>Borrar</a>
          </td>";
    echo "</tr>";
}

echo "</table>";

echo "<a href='../model/add_user.php'>Agregar Nuevo Usuario</a>";
?>