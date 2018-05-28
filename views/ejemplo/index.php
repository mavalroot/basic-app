<?php
session_start();
include_once '../../config/main-local.php';
use controllers\EjemploController;
use utilities\helpers\html\Components;

$pageTitle = 'Ejemplos';
$breadcrumps = [
    'Inicio' => '../site/index.php',
    $pageTitle => ''
];
Components::header($pageTitle, $breadcrumps);

$controller = new EjemploController();
$result = $controller->index($pagLimit, $pagOffset);
extract($result);

include_once "_read.php";
Components::pagination($rows, $page, $pagLimit, $pagOffset, $url);

Components::footer();
