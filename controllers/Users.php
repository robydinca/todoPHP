<?php
class Users {
    private $connection;

    /**
     * Constructor de la clase Users.
     *
     * @param object $connection Objeto de conexión a la base de datos (opcional).
     */
    public function __construct($connection = NULL){
        $this->connection = $connection;
    }

    /**
     * Sanitizes data before inserting into the database.
     *
     * @param array $data Datos a ser sanitizados.
     * @return array Datos sanitizados.
     */
    private function sanitizeData($data){
        $cleanData = array();
        foreach ($data as $key => $value) {
            $cleanData[$key] = $this->connection->real_escape_string($value);
        }
        return $cleanData;
    }

    /**
     * Inserta un nuevo usuario en la base de datos.
     *
     * @param array $userData Datos del usuario a insertar.
     * @return mixed Resultado de la operación de inserción en la base de datos.
     */
    public function insert($userData) {
        $userData = $this->sanitizeData($userData);
        $query = "INSERT INTO users (name, last_name, salt, login, password, role) VALUES ('".$userData['name']."', '".$userData['last_name']."', '".$userData['salt']."', '".$userData['login']."', '".$userData['password']."', '".$userData['role']."');";
        return $this->connection->query($query);
    }
    
     /**
     * Inserta nuevos campos de usuario en la base de datos.
     *
     * @param string|null $name Nombre del usuario.
     * @param string|null $last_name Apellido del usuario.
     * @param string|null $salt Valor 'salt' para la contraseña.
     * @param string|null $login Nombre de inicio de sesión del usuario.
     * @param string|null $password Contraseña del usuario.
     * @param string|null $role Rol del usuario.
     * @return mixed Resultado de la operación de inserción en la base de datos.
     */
    public function insertFields($name = NULL, $last_name = NULL, $salt = NULL, $login = NULL, $password = NULL, $role = NULL) {
      $query = "INSERT INTO users (name, last_name, salt, login, password, role) VALUES ('".$name."', '".$last_name."', '".$salt."', '".$login."', '".$password."', '".$role."');";
      return $this->connection->query($query);
  }

 /**
     * Actualiza los datos de un usuario existente en la base de datos.
     *
     * @param array $userData Datos del usuario a actualizar.
     * @return mixed Resultado de la operación de actualización en la base de datos.
     */
    public function updateUserData($userData) {
      if (is_array($userData) && !empty($userData['login'])) {
          $set = "SET ";
          
          if (!empty($userData['name'])){
              $set .= "name = '".$userData['name']."', ";
          }
          if (!empty($userData['last_name'])){
              $set .= "last_name = '".$userData['last_name']."', ";
          }
          if (!empty($userData['salt'])){
              $set .= "salt = '".$userData['salt']."', ";
          }
          if (!empty($userData['password'])){
              $set .= "password = '".$userData['password']."', ";
          }
          if (!empty($userData['role'])){
              $set .= "role = '".$userData['role']."', ";
          }
          $set = rtrim($set, ', '); // Elimina la última coma y el espacio
          
          $query = "UPDATE users ".$set." WHERE login = '".$userData['login']."';";
          return $this->connection->query($query);
      } else {
          return false; // O manejar el caso en el que $userData no es un array válido
      }
  }
 /**
     * Elimina un usuario de la base de datos.
     *
     * @param string $login Nombre de inicio de sesión del usuario a eliminar.
     * @return mixed Resultado de la operación de eliminación en la base de datos.
     */
    public function delete ($login){
      $query = "DELETE FROM users WHERE login = '".$login."';";
      return $this->connection->query($query);
  }

  /**
   * Obtiene la información de un usuario específico o de todos los usuarios.
   *
   * @param string|null $login Nombre de inicio de sesión del usuario (opcional).
   * @return mixed Datos del usuario solicitado o de todos los usuarios.
   */
  public function fetchUser($login = NULL){
      if ($login != NULL){
          $query = "SELECT * FROM users WHERE login = ?";
          $stmt = $this->connection->prepare($query);
          $stmt->bind_param("s", $login);
          $stmt->execute();
          $data = $stmt->get_result()->fetch_assoc();
          return $data;
      } else {
          $query = "SELECT * FROM users";
          $result = $this->connection->query($query);
          
          $users = array(); 
          
          while ($row = $result->fetch_assoc()) {
              $users[] = $row;
          }
          
          return $users;
      }
  }
    /**
     * Obtiene información de usuarios basada en varios campos.
     *
     * @param string|null $name Nombre del usuario (opcional).
     * @param string|null $last_name Apellido del usuario (opcional).
     * @param string|null $salt Valor 'salt' para la contraseña (opcional).
     * @param string|null $login Nombre de inicio de sesión del usuario (opcional).
     * @param string|null $password Contraseña del usuario (opcional).
     * @param string|null $role Rol del usuario (opcional).
     * @return mixed Datos de los usuarios que coinciden con los criterios de búsqueda.
     */
    public function fetchFields($name = NULL, $last_name = NULL, $salt = NULL, $login = NULL, $password = NULL, $role = NULL){
      $query = "SELECT * FROM users WHERE ";
      if ($name != NULL){
          $query .= "name = '".$name."' AND ";
      }
      if ($last_name != NULL){
          $query .= "last_name = '".$last_name."' AND ";
      }
      if ($salt != NULL){
          $query .= "salt = '".$salt."' AND ";
      }
      if ($login != NULL){
          $query .= "login = '".$login."' AND ";
      }
      if ($password != NULL){
          $query .= "password = '".$password."' AND ";
      }
      if ($role != NULL){
          $query .= "role = '".$role."' AND ";
      }
      $query = substr($query, 0, -5);
      $query .= ";";
      $result = $this->connection->query($query);
      
      $users = array();
      
      while ($row = $result->fetch_assoc()) {
          $users[] = $row;
      }
      
      return $users;
  }

  /**
   * Actualiza la imagen de perfil de un usuario en la base de datos.
   *
   * @param string $login Nombre de inicio de sesión del usuario.
   * @param string $imagePath Ruta de la imagen de perfil.
   * @return void
   */
  public function updateProfilePicture($login, $imagePath) {
      $query = "UPDATE users SET profile_picture = ? WHERE login = ?";
      $stmt = $this->connection->prepare($query);
      $stmt->bind_param("ss", $imagePath, $login);
      $stmt->execute();
      $stmt->close();
  }
}
?>
