<?php
class DbConnect
{
    private $connection;

    public function __construct()
    {
        require_once 'config.php';

        $dsn = "mysql:host=" . HOST . ";dbname=" . DB . ";port=" . PORT . ";charset=utf8mb4";

        try {
            $options = array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false,
            );
            $this->connection = new PDO($dsn, USER, PASSWORD, $options);

            // Verificar la conexión inmediatamente después de la creación
            if ($this->connection) {
                echo "Connection established successfully."; // Mensaje de conexión exitosa
            } else {
                echo "Connection could not be established."; // Mensaje de conexión fallida
            }
        } catch (PDOException $e) {
            die("Connection error: " . $e->getMessage());
        }
    }


    public function executeSecureQuery($query, $parameters = [])
    {
        try {
            $stmt = $this->connection->prepare($query);
            $stmt->execute($parameters);
            return $stmt;
        } catch (PDOException $e) {
            // Log error to a file or error handling system
            error_log("Error executing query: " . $e->getMessage());

            // Return false to indicate failure
            return false;
        }
    }

    public function getErrorMessage()
    {
        // Check if an error occurred before retrieving the error message
        if ($this->connection->errorCode() !== '00000') {
            $errorInfo = $this->connection->errorInfo();
            return implode(" ", $errorInfo);
        }

        return "No error occurred.";
    }



    public function closeConnection()
    {
        $this->connection = null;
    }
}
