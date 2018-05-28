<?php

namespace utilities\helpers\validation;

/**
 * @author María Valderrama Rodríguez <contact@mavalroot.es>
 * @copyright Copyright (c) 2018, María Valderrama Rodríguez
 *
 * Nos redirige a páginas de error.
 */
class Errors
{
    /**
     * El constructor es privado para que no se puedan crear instancias de
     * la clase.
     */
    private function __construct()
    {
    }

    /**
     * Nos lleva a una página de error 404.
     * @return null
     */
    public static function notFound()
    {
        header('Location: ' . ERRORS . '/404.php');
        return;
    }

    /**
     * Nos lleva a una página de error 403.
     * @return null
     */
    public static function forbidden()
    {
        header('Location: ' . ERRORS . '/403.php');
        return;
    }
}
