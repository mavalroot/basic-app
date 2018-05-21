<?php

session_start();
include_once '../../config/main-local.php';

use utilities\helpers\html\Components;
use controllers\DelegacionesController;

$pageTitle = "Crear nueva delegación";
$breadcrumps = [
    'Inicio' => '../site/index.php',
    'Delegaciones' => 'index.php',
    $pageTitle => ''
];
Components::header($pageTitle, $breadcrumps);

$controller = new DelegacionesController();
$delegacion = $controller->create();
?>

<form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
    <?php include_once '_form.php' ?>
</form>

<?php
Components::footer();
