<?php
include_once '../../config/main-local.php';

use controllers\AparatosController;

if (!isset($_POST['object_id'])) {
    echo 'El registro no ha podido ser eliminado.';
    return;
} else {
    $controller = new AparatosController();
    $controller->delete($_POST['object_id']);
}