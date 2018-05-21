<?php

session_start();
include_once '../../config/main-local.php';

use utilities\helpers\html\Components;
use controllers\AparatosController;

$breadcrumps = [
    'Inicio' => '../site/index.php',
    'Aparatos' => 'index.php',
    'Crear aparato' => ''
];
$pageTitle = "Nuevo registro";
Components::header($pageTitle, $breadcrumps);

$controller = new AparatosController();
$aparato = $controller->create();
?>

<form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
    <?php include_once '_form.php' ?>
</form>

<?php
Components::footer();
