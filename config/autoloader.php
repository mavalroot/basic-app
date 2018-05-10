<?php
/**
 * Clase para la carga automática de clases.
 */
class Autoloader
{
    /**
     * Carga las clases de forma automática, convirtiendo el "use" en una
     * url.
     * @param string $className Nombre de la clase.
     * @return bool
     */
    public static function loader($className)
    {
        $folders = [ROOT . '/'];
        foreach ($folders as $value) {
            $filename = $value . str_replace("\\", '/', $className) . ".php";
            if (file_exists($filename)) {
                include($filename);
                if (class_exists($className)) {
                    return true;
                }
            }
        }
        return false;
    }
}
spl_autoload_register('Autoloader::loader');
