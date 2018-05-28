<?php
session_start();
include_once '../../config/main-local.php';
use models\Ejemplo;
use utilities\helpers\html\Components;

use utilities\helpers\validation\Errors;

if (!isset($_GET['id'])) {
    Errors::notFound();
}
$id = $_GET['id'];
$ejemplo = new Ejemplo(['id' => $id]);
if (!$ejemplo->readOne()) {
    Errors::notFound();
}
;

Components::header($aparato->getFullName(), [], false);
?>
<?= nl2br($aparato->getQRData()) ?>
<?php
Components::footer(false);
