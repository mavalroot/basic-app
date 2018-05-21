<?php
include_once '../../config/main-local.php';

use controllers\RolesController;

if (!isset($_POST['object_id'])) {
    echo 'El rol no ha podido ser eliminado.';
    return;
} else {
    $controller = new RolesController();
    $controller->delete($_POST['object_id']);
}
