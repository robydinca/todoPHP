<?php
/**
 * Clase DbConnect
 * 
 * Esta clase gestiona la conexión a la base de datos utilizando PDO y permite ejecutar consultas de manera segura.
 */
class DbConnect
{
    /**
     * @var PDO $connection - Almacena la conexión a la base de datos.
     */
    private $connection;

    /**
     * Constructor de la clase. Establece la conexión a la base de datos utilizando los datos de configuración.
     */
    public function __construct()
    {
        require_once 'config.php';

        // Configurar DSN para la conexión PDO
        $dsn = "mysql:host=" . HOST . ";dbname=" . DB . ";port=" . PORT . ";charset=utf8mb4";

        try {
            // Opciones de configuración para la conexión PDO
            $options = array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false,
            );

            // Establecer la conexión PDO
            $this->connection = new PDO($dsn, USER, PASSWORD, $options);

            // Verificar la conexión inmediatamente después de la creación
            if ($this->connection) {
                echo "Connection established successfully."; // Mensaje de conexión exitosa
            } else {
                echo "Connection could not be established."; // Mensaje de conexión fallida
            }
        } catch (PDOException $e) {
            // En caso de error, mostrar un mensaje y terminar la ejecución
            die("Connection error: " . $e->getMessage());
        }
    }

    /**
     * Método para ejecutar consultas preparadas de manera segura.
     * 
     * @param string $query      - Consulta SQL a ejecutar.
     * @param array $parameters  - Parámetros de la consulta (opcional).
     * @return PDOStatement|false - Retorna el objeto de sentencia PDO o false en caso de error.
     */
    public function executeSecureQuery($query, $parameters = [])
    {
        try {
            $stmt = $this->connection->prepare($query);
            $stmt->execute($parameters);
            return $stmt;
        } catch (PDOException $e) {
            // Registrar el error en un archivo o sistema de manejo de errores
            error_log("Error executing query: " . $e->getMessage());

            // Retornar false para indicar fallo
            return false;
        }
    }

    /**
     * Método para obtener el mensaje de error de la conexión.
     * 
     * @return string - Mensaje de error de la conexión.
     */
    public function getErrorMessage()
    {
        // Verificar si ocurrió un error antes de obtener el mensaje de error
        if ($this->connection->errorCode() !== '00000') {
            $errorInfo = $this->connection->errorInfo();
            return implode(" ", $errorInfo);
        }

        return "No error occurred.";
    }

    /**
     * Método para cerrar la conexión a la base de datos.
     */
    public function closeConnection()
    {
        $this->connection = null;
    }
}
?>