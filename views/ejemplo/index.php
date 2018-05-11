<?php
session_start();
include_once '../../config/main-local.php';
use utilities\helpers\html\Components;

use controllers\AparatosController;

$breadcrumps = [
    'Index' => '../site/index.php',
    'Aparatos' => ''
];
$pageTitle = "Registro completo";
Components::header($pageTitle, $breadcrumps);

$controller = new AparatosController();
$controller = $controller->index($pagLimit, $pagOffset);
extract($controller);

include_once "_read.php";
Components::pagination($rows, $page, $pagLimit, $pagOffset, $url);

Components::footer();
