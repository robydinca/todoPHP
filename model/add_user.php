<?php
// Importa la conexión a la base de datos y la clase Users
require_once "../config/config.php";
require_once "../controllers/Users.php";

// Crea una instancia de la clase Users pasando la conexión como parámetro
$conexion = new mysqli(HOST, USER, PASSWORD, DB, PORT);
$users = new Users($conexion);

// Ejemplo de cómo usar los métodos de la clase Users

// Insertar un nuevo usuario
$newUserData = array(
    'name' => 'John',
    'last_name' => 'Doe',
    'salt' => 'random_salt',
    'login' => 'johndoe',
    'password' => 'hashed_password',
    'role' => 'user'
);
$resultInsert = $users->insert($newUserData);
if ($resultInsert) {
    echo "Usuario insertado correctamente.";
} else {
    echo "Error al insertar el usuario.";
}

// Obtener información de un usuario específico por su login
$specificUser = $users->fetchUser('johndoe');
if ($specificUser) {
    echo "Información del usuario:";
    print_r($specificUser);
} else {
    echo "Usuario no encontrado.";
}

// Actualizar datos de un usuario
$userToUpdate = array(
    'login' => 'johndoe',
    'name' => 'John Updated',
    'last_name' => 'Doe Updated',
    'password' => 'updated_password'
);
$resultUpdate = $users->updateUserData($userToUpdate);
if ($resultUpdate) {
    echo "Datos del usuario actualizados correctamente.";
} else {
    echo "Error al actualizar los datos del usuario.";
}

// Eliminar un usuario
$resultDelete = $users->delete('johndoe');
if ($resultDelete) {
    echo "Usuario eliminado correctamente.";
} else {
    echo "Error al eliminar el usuario.";
}
?>