<?php
session_start();
include_once '../../config/main-local.php';
use controllers\EjemploController;

use utilities\helpers\html\Html;
use utilities\helpers\validation\Errors;
use utilities\helpers\html\Components;

// Se obtiene el ID del registro que se va a mostrar.
if (!isset($_GET['id'])) {
    Errors::notFound();
}
$id = $_GET['id'];
$ejemplo = EjemploController::view($id);

$pageTitle = $ejemplo->ejemplo;
$breadcrumps = [
    'Inicio' => '../site/index.php',
    'Ejemplos' => 'index.php',
    $pageTitle => ''
];
Components::header($pageTitle, $breadcrumps);

$name = str_replace(' ', '_', $ejemplo->ejemplo);
?>

<div class="row mb-2">
    <div class="col-sm-6">
        <button type="button" class="guardar-qr btn btn-sm btn-primary" data-name="<?= $name ?>">
            <i class="fas fa-download"></i> Guardar Código QR
        </button>

        <button class="btn btn-sm btn-primary" id="export" data-id="<?= $id ?>" data-name="<?= $name ?>">
            <i class="fas fa-download"></i> Guardar como PDF
        </button>
    </div>
    <div class='col-sm-6 text-right'>
        <a href='create.php' class='btn btn-sm btn-primary'>
            <i class="fas fa-plus"></i> Nuevo ejemplo
        </a>
        <a href='update.php?id=<?= $ejemplo->id ?>' class='btn btn-sm btn-primary'>
            <i class="fas fa-edit"></i> Modificar ejemplo
        </a>
    </div>
</div>

<div id="content" class="table-responsive">
    <table class='table table-striped'>
        <?= Html::form($ejemplo)->multiTrTable([
            'exclude' => ['id']
        ]); ?>
    </table>
</div>

<div class="row">
    <div id="printable-qrcode" style="background: white; max-width: 400px;">
        <div style="display: flex; align-items: center;">
            <img src="http://placehold.it/100" style="height: 80px;" alt=""> <h3>Titulo</h3>
        </div>
        <div style="display: flex" class="mt-2">
            <div id="qrcode" title="Información"></div>
            <div class="ml-2">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
            </div>
        </div>
    </div>
    <a href="#" class="guardar-qr" data-name="<?= $name ?>">Guardar</a>
</div>
</div>

<?php

Components::footer();
