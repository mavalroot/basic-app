<?php
session_start();
include_once '../../config/main-local.php';

use controllers\UsuariosController;

if (!isset($_POST['object_id'])) {
    echo 'El ejemplo no ha podido ser eliminado.';
    return;
} else {
    $controller = new UsuariosController();
    $controller->delete($_POST['object_id']);
}
