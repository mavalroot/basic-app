<?php
session_start();
include_once '../../config/main-local.php';
use controllers\UsuariosController;

use utilities\helpers\html\Html;
use utilities\helpers\validation\Errors;
use utilities\helpers\html\Components;
use controllers\AparatosController;

// Se obtiene el ID del registro que se va a mostrar.
if (!isset($_GET['id'])) {
    Errors::notFound();
}
$id = $_GET['id'];

$breadcrumps = [
    'Index' => '../site/index.php',
    'Aparatos' => 'index.php',
    "Detalle aparato$id" => ''
];
$pageTitle = "Consultar un registro";
Components::header($pageTitle, $breadcrumps);

$usuario = UsuariosController::view($id);
?>

<div class='right-button-margin'>
    <a href='index.php' class='btn btn-primary float-right'>
        <i class="fas fa-list-ul"></i> Consultar todos los registros
    </a>
</div>

<div id="content" class="table-responsive">
    <table class='table table-striped'>
        <?= Html::form($usuario)->multiTrTable([
            'exclude' => ['id', 'created_at', 'delegacion_id']
        ]); ?>
        <?= Html::form($usuario, 'delegacion_id')->trTable([
            'value' => function ($model) {
                return $model->getNombreDelegacion();
            },
        ]) ?>
    </table>
</div>

<button class="btn btn-md btn-success float-right" id="export" data-id="<?= $id ?>" data-name="aparato">
    Guardar como PDF
</button>

<?php

Components::footer();
