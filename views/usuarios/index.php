<?php
session_start();
include_once '../../config/main-local.php';
use utilities\helpers\html\Components;

use controllers\UsuariosController;

$pageTitle = 'Usuarios';
$breadcrumps = [
    'Inicio' => '../site/index.php',
    $pageTitle => ''
];
Components::header($pageTitle, $breadcrumps);

$controller = new UsuariosController();
$controller = $controller->index($pagLimit, $pagOffset);
extract($controller);

include_once "_read.php";
Components::pagination($rows, $page, $pagLimit, $pagOffset, $url);

Components::footer();
