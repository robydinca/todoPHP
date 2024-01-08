<?php
class Security {
    /**
     * Constructor para Security.
     * Inicia una sesión si no está activa.
     */
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start(); 
        }
    }

    /**
     * Inicia sesión de un usuario almacenando el inicio de sesión y el rol en variables de sesión.
     * @param string $login Nombre de inicio de sesión del usuario.
     * @param string $role El rol asociado al usuario.
     */
    public function login($login, $role) {
        $_SESSION['login'] = $login;
        $_SESSION['role'] = $role;
    }

    /**
     * Cierra la sesión del usuario actual limpiando las variables de sesión.
     */
    public function logout() {
        session_unset();
        session_destroy();
    }

    /**
     * Verifica si un usuario está autenticado.
     * @return bool Verdadero si el usuario está autenticado, falso en caso contrario.
     */
    public function isAuth() {
        return isset($_SESSION['login']);
    }

    /**
     * Obtiene el rol del usuario autenticado actualmente.
     * @return string|null El rol del usuario autenticado, o null si no está autenticado.
     */
    public function getRole() {
        if ($this->isAuth()) {
            return $_SESSION['role'];
        }
        return null;
    }

    /**
     * Verifica si el usuario autenticado actualmente tiene el rol requerido.
     * @param string $requiredRole El rol necesario para el permiso.
     * @return bool Verdadero si el usuario tiene el rol requerido, falso en caso contrario.
     */
    public function hasPermission($requiredRole) {
        if ($this->isAuth() && $_SESSION['role'] === $requiredRole) {
            return true;
        }
        return false;
    }

    /**
     * Genera una clave CSRF y la almacena en la sesión del usuario.
     * @param string $key Nombre de la clave CSRF.
     * @return string La clave CSRF generada.
     */
    public function generateCSRFKey($key) {
        $csrfKey = bin2hex(random_bytes(32));
        $_SESSION[$key] = $csrfKey;
        return $csrfKey;
    }

    /**
     * Verifica si la clave CSRF proporcionada coincide con la almacenada en la sesión.
     * @param string $key Nombre de la clave CSRF.
     * @param string $keyValue Valor de la clave CSRF proporcionada.
     * @return bool Verdadero si las claves CSRF coinciden, falso en caso contrario.
     */
    public function verifyCSRFKey($key, $keyValue) {
        if (isset($_SESSION[$key]) && hash_equals($_SESSION[$key], $keyValue)) {
            unset($_SESSION[$key]);
            return true;
        }
        return false;
    }

    /**
     * Genera un token de autenticación seguro.
     * @return string El token de autenticación generado.
     */
    public function generateAuthenticationToken() {
        return bin2hex(random_bytes(32));
    }

    /**
     * Verifica si un token de autenticación es válido.
     * @param string $token El token de autenticación proporcionado.
     * @param string $storedToken El token de autenticación almacenado.
     * @return bool Verdadero si el token de autenticación es válido, falso en caso contrario.
     */
    public function verifyAuthenticationToken($token, $storedToken) {
        return hash_equals($storedToken, $token);
    }

    /**
     * Obtiene el nombre de inicio de sesión del usuario autenticado actualmente.
     * @return string|null El nombre de inicio de sesión del usuario autenticado, o null si no está autenticado.
     */
    public function getCurrentUserLogin() {
        if ($this->isAuth()) {
            return $_SESSION['login'];
        }
        return null;
    }
}

?>
