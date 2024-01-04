<?php
require_once "ConfigWriter.php";
require_once "DbConnect.php";

if (isset($_POST["host"]) && isset($_POST["user"]) && isset($_POST["password"]) && isset($_POST["nombreBaseDatos"])) {
    $host = $_POST["host"];
    $user = $_POST["user"];
    $password = $_POST["password"];
    $nombreBaseDatos = $_POST["nombreBaseDatos"];
    $puerto = $_POST["puerto"];

    $configWriter = new ConfigWriter();
    $createdFile = $configWriter->writeConfigFile($host, $user, $password, $nombreBaseDatos, $puerto);

    if ($createdFile) {
        echo "Configuration file created: " . $createdFile;

        // Creación de tablas
        $db = new DbConnect();


        $queries[] = "CREATE TABLE IF NOT EXISTS users (
          name VARCHAR(50) NOT NULL,
          last_name VARCHAR(50) NOT NULL,
          salt VARCHAR(20) NOT NULL,
          login VARCHAR(50) NOT NULL PRIMARY KEY,
          email VARCHAR(150) NOT NULL,
          password VARCHAR(512) NOT NULL,
          role enum('admin', 'librarian', 'user') NOT NULL
      )";

        $queries[] = "CREATE TABLE IF NOT EXISTS tasks (
          task_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
          title VARCHAR(50) NOT NULL,
          content VARCHAR(500) NOT NULL,
          date DATE NOT NULL,
          status ENUM('pending', 'in progress', 'completed') NOT NULL,
          duration INT NOT NULL,
          user_login VARCHAR(50) NOT NULL,
          FOREIGN KEY (user_login) REFERENCES users(login)
      )";

        $queries[] = "CREATE TABLE IF NOT EXISTS courses (
          course_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
          name VARCHAR(50) NOT NULL,
          description VARCHAR(500) NOT NULL,
          status ENUM('pending', 'in progress', 'finished') NOT NULL,
          duration INT NOT NULL,
          user_login VARCHAR(50) NOT NULL,
          FOREIGN KEY (user_login) REFERENCES users(login)
      )";

        $queries[] = "CREATE TABLE IF NOT EXISTS menus (
          menu_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
          name VARCHAR(50) NOT NULL,
          description VARCHAR(500) NOT NULL,
          status ENUM('pending', 'in progress', 'completed') NOT NULL,
          duration INT NOT NULL,
          user_login VARCHAR(50) NOT NULL,
          FOREIGN KEY (user_login) REFERENCES users(login)
      )";

        $queries[] = "CREATE TABLE IF NOT EXISTS routines (
          routine_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
          name VARCHAR(50) NOT NULL,
          description VARCHAR(500) NOT NULL,
          status ENUM('pending', 'in progress', 'completed') NOT NULL,
          duration INT NOT NULL,
          user_login VARCHAR(50) NOT NULL,
          FOREIGN KEY (user_login) REFERENCES users(login)
      )";

        $queries[] = "CREATE TABLE IF NOT EXISTS notes (
          note_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
          title VARCHAR(50) NOT NULL,
          content VARCHAR(500) NOT NULL,
          date DATE NOT NULL,
          status ENUM('pending', 'in progress', 'completed') NOT NULL,
          task_id INT,
          FOREIGN KEY (task_id) REFERENCES tasks(task_id),
          course_id INT,
          FOREIGN KEY (course_id) REFERENCES courses(course_id),
          menu_id INT,
          FOREIGN KEY (menu_id) REFERENCES menus(menu_id),
          routine_id INT,
          FOREIGN KEY (routine_id) REFERENCES routines(routine_id),
          user_login VARCHAR(50) NOT NULL,
          FOREIGN KEY (user_login) REFERENCES users(login)
      )";

        // Añadir otras consultas de creación de tablas aquí

        foreach ($queries as $query) {
            if ($db->executeSecureQuery($query) !== false) {
                echo "Table created successfully<br>";
                //header("Location: ./register.php");
            } else {
                echo "Error creating table: " . $db->getErrorMessage() . "<br>";
            }
        }

        if (!$db) {
            die("Connection error: Unable to establish connection.");
        } else {
            echo "Connection established successfully.<br>";
            header ("Location: ./register.php");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <title>todoBetter</title>
    <link rel="stylesheet" href="./styles/forms.css">
</head>

<body class="install" style="display:flex; flex-direction:column; padding: 40px;">

    <form action="" method="post" class="installForm">
        <h1>Instalación para el primer user Administrador</h1>
        <input type="text" name="host" placeholder="Host" required>
        <input type="text" name="user" placeholder="user" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <input type="text" name="nombreBaseDatos" placeholder="Nombre Base de Datos" required>
        <input type="text" name="puerto" placeholder="Puerto" required>
        <input type="submit" value="Aceptar" class="boton">
    </form>

</body>

</html>