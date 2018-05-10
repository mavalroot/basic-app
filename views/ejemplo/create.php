<?php

session_start();
include_once '../../config/main-local.php';

use utilities\helpers\html\Components;
use controllers\InhumacionesController;

$breadcrumps = [
    'Index' => '../site/index.php',
    'Inhumaciones' => 'index.php',
    'Crear inhumación' => ''
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
$controller = new InhumacionesController();
$inhumacion = $controller->create();
?>

<form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
    <?php include_once '_form.php' ?>
</form>

<?php
Components::footer();
