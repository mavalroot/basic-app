<?php
session_start();
include_once '../../config/main-local.php';
use models\Aparatos;
use utilities\helpers\html\Components;

use utilities\helpers\validation\Errors;

if (!isset($_GET['id'])) {
    Errors::notFound();
}
$id = $_GET['id'];
$aparato = new Aparatos(['id' => $id]);
if (!$aparato->readOne()) {
    Errors::notFound();
}
$especifico = $aparato->getDatosAsociados();

Components::header($aparato->getFullName(), [], false);
?>
<?= nl2br($aparato->getQRData()) ?>
<?= nl2br($especifico->getQRData(['aparato_id'])) ?>
<?php
Components::footer(false);
