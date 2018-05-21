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
        $rol = new Roles();
        if (!$rol->isLogged()) {
            header("Location: ../roles/login.php");
            exit;
        }
    }

    /**
     * Comprueba que el rol del rol actual coincida con el rol pasado
     * como parámetro.
     * @param  int $check
     * @return bool
     */
    public static function checkPermission($check)
    {
        return isset($_SESSION['permiso_id']) && $_SESSION['permiso_id'] == $check;
    }

    /**
     * Comprueba que el permiso del rol actual sea administrador.
     * @param string|array $check Valor a comprobar. Debe ser un permiso existente
     * de la tabla permisos.
     * @return bool
     */
    public function permission($check)
    {
        $actual = $_SESSION['permiso_id'];
        if (is_string($check)) {
            if (!isset($actual) || $actual !== $check) {
                Errors::forbidden();
            }
        }
        if (is_array($check)) {
            if (!isset($actual) || !in_array($actual, $check)) {
                Errors::forbidden();
            }
        }
    }
}
