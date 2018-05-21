<?php

session_start();
include_once '../../config/main-local.php';

use utilities\helpers\html\Components;
use controllers\UsuariosController;

$pageTitle = "Crear nuevo usuario";
$breadcrumps = [
    'Inicio' => '../site/index.php',
    'Usuarios' => 'index.php',
    $pageTitle => ''
];
Components::header($pageTitle, $breadcrumps);

$controller = new UsuariosController();
$usuario = $controller->create();
?>

<form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
    <?php include_once '_form.php' ?>
</form>

<?php
Components::footer();
