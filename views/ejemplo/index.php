<?php
session_start();
include_once '../../config/main-local.php';
use utilities\helpers\html\Components;

use controllers\InhumacionesController;

$breadcrumps = [
    'Index' => '../site/index.php',
    'Inhumaciones' => ''
];
$pageTitle = "Registro completo";
Components::header($pageTitle, $breadcrumps);

$controller = new InhumacionesController();
$controller = $controller->index($pagLimit, $pagOffset);
extract($controller);

include_once "_read.php";
Components::pagination($rows, $page, $pagLimit, $pagOffset, $url);

Components::footer();
