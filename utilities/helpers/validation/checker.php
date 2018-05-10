<?php
namespace utilities\helpers\validation;

use models\Roles;

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
}
