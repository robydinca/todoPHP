<?php
/**
 * Clase ConfigWriter
 * 
 * Esta clase se utiliza para escribir el archivo de configuración config.php con los datos de conexión proporcionados.
 */
class ConfigWriter {
    /**
     * writeConfigFile
     * 
     * Este método crea un archivo config.php con los datos de conexión proporcionados.
     * 
     * @param string $host            - El host del servidor de la base de datos.
     * @param string $user            - El nombre de usuario de la base de datos.
     * @param string $password        - La contraseña de la base de datos.
     * @param string $nombreBaseDatos - El nombre de la base de datos.
     * @param string $puerto          - El puerto del servidor de la base de datos.
     * @return bool                   - Retorna true si la operación de escritura es exitosa, de lo contrario false.
     */
    public function writeConfigFile($host, $user, $password, $nombreBaseDatos, $puerto) {
        // Contenido del archivo de configuración
        $configContenido = '<?php' . "\n";
        $configContenido .= 'define("HOST", "' . $host . '");' . "\n";
        $configContenido .= 'define("USER", "' . $user . '");' . "\n";
        $configContenido .= 'define("PASSWORD", "' . $password . '");' . "\n";
        $configContenido .= 'define("DB", "' . $nombreBaseDatos . '");' . "\n";
        $configContenido .= 'define("PORT", "' . $puerto . '");' . "\n";
        $configContenido .= '?' . '>' . "\n";

        // Escribir en el archivo config.php
        $archivo = fopen("config.php", "w");
        fwrite($archivo, $configContenido);
        fclose($archivo);

        return true; // Operación exitosa
    }
}


?>
