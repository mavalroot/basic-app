<?php

session_start();
include_once '../../config/main-local.php';

use utilities\helpers\html\Components;
use controllers\RolesController;

$pageTitle = "Crear nuevo rol";
$breadcrumps = [
    'Inicio' => '../site/index.php',
    'Roles' => 'index.php',
    $pageTitle => ''
];
Components::header($pageTitle, $breadcrumps);

$controller = new RolesController();
$rol = $controller->create();
?>

<form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
    <?php include_once '_form.php' ?>
</form>

<?php
Components::footer();
