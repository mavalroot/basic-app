<?php
session_start();
include_once '../../config/main-local.php';

use controllers\EjemploController;

if (!isset($_POST['object_id'])) {
    echo 'El ejemplo no ha podido ser eliminado.';
    return;
} else {
    $controller = new EjemploController();
    $controller->delete($_POST['object_id']);
}
