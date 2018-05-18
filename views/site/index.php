<?php
session_start();
include_once '../../config/main-local.php';
use utilities\helpers\html\Components;

// $breadcrumps = [
//     'Inicio' => '',
// ];
$pageTitle = "Inicio";

Components::header($pageTitle, []);
?>

<h3>Instrucciones</h3>
Maybe
<?php
Components::footer();
