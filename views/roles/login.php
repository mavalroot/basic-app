<?php
session_start();
include_once '../../config/main-local.php';

use controllers\RolesController;
use utilities\helpers\html\Components;

$pageTitle = "Login";

Components::header($pageTitle, [], true, false);

$controller = new RolesController();
$controller->login();

include '_login.php';

Components::footer();
