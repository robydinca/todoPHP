<?php
class Notes
{
    private $connection;

    /**
     * Constructor de la clase notes.
     *
     * @param object $connection Objeto de conexión a la base de datos (opcional).
     */
    public function __construct($connection = NULL)
    {
        $this->connection = $connection;
    }

    /**
     * Sanitiza los datos antes de insertarlos en la base de datos.
     *
     * @param array $data Datos a ser sanitizados.
     * @return array Datos sanitizados.
     */
    private function sanitizeData($data)
    {
        $cleanData = array();
        foreach ($data as $key => $value) {
            $cleanData[$key] = $this->connection->real_escape_string($value);
        }
        return $cleanData;
    }

    /**
     * Inserta una nueva nota en la base de datos.
     *
     * @param array $noteData Datos de la nota a insertar.
     * @return mixed Resultado de la operación de inserción en la base de datos.
     */
    public function insert($noteData)
    {
        $noteData = $this->sanitizeData($noteData);

        $title = isset($noteData['title']) ? "'" . $noteData['title'] . "'" : "NULL";
        $content = isset($noteData['content']) ? "'" . $noteData['content'] . "'" : "NULL";
        $date = isset($noteData['date']) ? "'" . $noteData['date'] . "'" : "NULL";
        $status = isset($noteData['status']) ? "'" . $noteData['status'] . "'" : "NULL";
        $task_id = isset($noteData['task_id']) && $noteData['task_id'] !== '' ? "'" . $this->connection->real_escape_string($noteData['task_id']) . "'" : "NULL";
        $course_id = isset($noteData['course_id']) && $noteData['course_id'] !== '' ? "'" . $noteData['course_id'] . "'" : "NULL";
        $menu_id = isset($noteData['menu_id']) && $noteData['menu_id'] !== '' ? "'" . $noteData['menu_id'] . "'" : "NULL";
        $routine_id = isset($noteData['routine_id']) && $noteData['routine_id'] !== '' ? "'" . $noteData['routine_id'] . "'" : "NULL";
        $user_login = isset($noteData['user_login']) ? "'" . $noteData['user_login'] . "'" : "NULL";

        $query = "INSERT INTO notes (title, content, date, status, task_id, course_id, menu_id, routine_id, user_login) 
              VALUES ($title, $content, $date, $status, $task_id, $course_id, $menu_id, $routine_id, $user_login)";

        return $this->connection->query($query);
    }



    /**
     * Actualiza los datos de una nota existente en la base de datos.
     *
     * @param array $noteData Datos de la nota a actualizar.
     * @return mixed Resultado de la operación de actualización en la base de datos.
     */
    public function updateNoteData($noteData)
    {
        if (is_array($noteData) && !empty($noteData['note_id'])) {
            $set = "SET ";

            // Comprobar cada campo para su actualización
            foreach ($noteData as $key => $value) {
                if ($key !== 'note_id') { // Evitar actualizar la clave primaria
                    $set .= "$key = '" . $this->connection->real_escape_string($value) . "', ";
                }
            }

            $set = rtrim($set, ', '); // Elimina la última coma y el espacio

            $query = "UPDATE notes " . $set . " WHERE note_id = '" . $noteData['note_id'] . "';";
            return $this->connection->query($query);
        } else {
            return false; // Manejar el caso en el que $noteData no es un array válido o no tiene el campo 'note_id'
        }
    }

    /**
     * Elimina una nota de la base de datos.
     *
     * @param int $note_id ID de la nota a eliminar.
     * @return mixed Resultado de la operación de eliminación en la base de datos.
     */
    public function delete($note_id)
    {
        $query = "DELETE FROM notes WHERE note_id = '" . $note_id . "';";
        return $this->connection->query($query);
    }

    /**
     * Obtiene la información de una nota específica o de todas las notes.
     *
     * @param int|null $note_id ID de la nota (opcional).
     * @return mixed Datos de la nota solicitada o de todas las notes.
     */
    public function fetchNote($note_id = NULL)
    {
        if ($note_id != NULL) {
            $query = "SELECT * FROM notes WHERE note_id = ?";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param("i", $note_id);
            $stmt->execute();
            $data = $stmt->get_result()->fetch_assoc();
            return $data;
        } else {
            $query = "SELECT * FROM notes";
            $result = $this->connection->query($query);

            $notes = array();

            while ($row = $result->fetch_assoc()) {
                $notes[] = $row;
            }

            return $notes;
        }
    }

    /**
     * Obtiene la información de una nota específica.
     *
     * @param int $note_id ID de la nota.
     * @param string $user_login Nombre de inicio de sesión del usuario.
     * @return mixed Datos de la nota solicitada.
     */
    public function getNoteByIdAndUser($note_id, $user_login)
    {
        $query = "SELECT * FROM notes WHERE note_id = ? AND user_login = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("is", $note_id, $user_login);
        $stmt->execute();
        $data = $stmt->get_result()->fetch_assoc();
        return $data;
    }
}
