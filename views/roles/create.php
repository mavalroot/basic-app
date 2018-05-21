<?php

session_start();
include_once '../../config/main-local.php';

use utilities\helpers\html\Components;
use controllers\RolesController;

$breadcrumps = [
    'Inicio' => '../site/index.php',
    'Aparatos' => 'index.php',
    'Crear aparato' => ''
];
$pageTitle = "Nuevo registro";
Components::header($pageTitle, $breadcrumps);

?>

<div class='right-button-margin'>
    <a href='index.php' class='btn btn-primary float-right'>
        <i class="fas fa-list-ul"></i> Consultar todos los registros
    </a>
</div>

<?php
$controller = new RolesController();
$rol = $controller->create();
?>

<form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
    <?php include_once '_form.php' ?>
</form>

<?php
Components::footer();
