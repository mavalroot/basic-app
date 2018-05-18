<?php
session_start();
include_once '../../config/main-local.php';

use controllers\DelegacionesController;

if (!isset($_POST['object_id'])) {
    echo 'El registro no ha podido ser eliminado.';
    return;
} else {
    $controller = new DelegacionesController();
    $controller->delete($_POST['object_id']);
}
