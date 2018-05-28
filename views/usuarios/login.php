<?php
session_start();
include_once '../../config/main-local.php';

use controllers\UsuariosController;
use utilities\helpers\html\Components;

$pageTitle = "Login";

Components::header($pageTitle, [], true, false);

$controller = new UsuariosController();
$controller->login();

include '_login.php';

Components::footer();
