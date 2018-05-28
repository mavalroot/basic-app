<?php
include_once '../../config/main-local.php';

use controllers\UsuariosController;

if (!isset($_POST['object_id'])) {
    echo 'El usuario no ha podido ser eliminado.';
    return;
} else {
    $controller = new UsuariosController();
    $controller->delete($_POST['object_id']);
}
