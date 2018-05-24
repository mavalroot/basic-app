<?php
namespace utilities\helpers\validation;

use models\Roles;
use models\Permisos;

/**
 * Clase que se encarga de hacer diversas comprobaciones.
 */
class Checker
{
    /**
     * Comprueba que el rol esté loggeado. En el caso de que no lo esté
     * lo redirige a la pantalla de login.
     */
    public static function checkLogin()
    {
        if (!static::checkLogged()) {
            header("Location: ../roles/login.php");
            $_SESSION['previousUrl'] = $_SERVER['REQUEST_URI'];
            exit;
        }
    }

    /**
     * Chequea si existe un usuario conectado.
     * @return bool
     */
    public static function checkLogged()
    {
        $rol = new Roles();
        return $rol->isLogged();
    }

    /**
     * Comprueba que el rol del rol actual coincida con el rol pasado
     * como parámetro.
     * @param  int $check
     * @return bool
     */
    public static function checkPermission($check)
    {
        $actual = isset($_SESSION['permiso_id']) ? $_SESSION['permiso_id'] : null;
        if (is_string($check)) {
            $permiso = Permisos::getPermisoId($check);
            if (!isset($permiso) || !isset($actual) || $actual !== $permiso) {
                return false;
            }
        }
        if (is_array($check)) {
            $new = [];
            foreach ($check as $value) {
                $new[] = Permisos::getPermisoId($value);
            }
            if (!isset($actual) || !in_array($actual, $new)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Comprueba que el permiso del rol actual sea administrador.
     * @param string|array $check Valor a comprobar. Debe ser un permiso existente
     * de la tabla permisos.
     * @return bool
     */
    public static function permission($check)
    {
        if (!static::checkPermission($check)) {
            Errors::forbidden();
        }
    }
}
