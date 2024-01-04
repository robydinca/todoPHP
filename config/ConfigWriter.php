<?php
class ConfigWriter {
    public function writeConfigFile($host, $user, $password, $nombreBaseDatos, $puerto) {
        $configContenido = '<?php' . "\n";
        $configContenido .= 'define("HOST", "' . $host . '");' . "\n";
        $configContenido .= 'define("USER", "' . $user . '");' . "\n";
        $configContenido .= 'define("PASSWORD", "' . $password . '");' . "\n";
        $configContenido .= 'define("DB", "' . $nombreBaseDatos . '");' . "\n";
        $configContenido .= 'define("PORT", "' . $puerto . '");' . "\n";
        $configContenido .= '?' . '>' . "\n";

        $archivo = fopen("config.php", "w");
        fwrite($archivo, $configContenido);
        fclose($archivo);

        return true;
    }
}

?>
