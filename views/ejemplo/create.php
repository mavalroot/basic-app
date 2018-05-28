<?php

session_start();
include_once '../../config/main-local.php';

use utilities\helpers\html\Components;
use controllers\EjemploController;

$pageTitle = "Crear nuevo ejemplo";
$breadcrumps = [
    'Inicio' => '../site/index.php',
    'Ejemplos' => 'index.php',
    $pageTitle => ''
];
Components::header($pageTitle, $breadcrumps);

$controller = new EjemploController();
$ejemplo = $controller->create();
?>

<form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
    <?php include_once '_form.php' ?>
</form>

<?php
Components::footer();
