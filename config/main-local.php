<?php

/**
 * Para el paginador. Es la página en la que nos encontramso actualmente.
 * Se recibe a través de GET, y por defecto es 1.
 * @var int
 */
$page = isset($_GET['page']) ? $_GET['page'] : 1;

/**
 * Número de filas que se muestra por cada página.
 * @var int
 */
$pagLimit = 5;

/**
 * Calcula el offset para saber en qué fila empieza cada página.
 * @var int
 */
$pagOffset = ($pagLimit * $page) - $pagLimit;

/**
 * Forma la base url de la aplicación.
 * @var string
 */
if (isset($_SERVER['HTTPS'])) {
    $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https://" : "http://";
} else {
    $protocol = 'http://';
}

/**
 * Define una constante ROOT para acceder a la raíz más fácilmente.
 * @var string
 */
define('ROOT', __DIR__ . '/..');
define('BASEURL', $protocol . $_SERVER['SERVER_NAME']);
define('ERRORS', BASEURL . '/errors');

unset($protocol);

/**
 * Solicita el autoloader.
 */
require_once 'autoloader.php';
