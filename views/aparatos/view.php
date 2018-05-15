<?php
session_start();
include_once '../../config/main-local.php';
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

$result = AparatosController::view($id);
extract($result);
?>

<div class='right-button-margin'>
    <a href='index.php' class='btn btn-primary float-right'>
        <i class="fas fa-list-ul"></i> Consultar todos los registros
    </a>
</div>

<div id="content" class="table-responsive">
    <table class='table table-striped'>
        <?= Html::form($aparato, 'usuario_id')->trTable([
            'value' => function ($model) {
                $user = $model->getUsuario();
                return isset($user->nombre) ? $user->nombre : '';
            },
        ]) ?>
        <?= Html::form($aparato, 'delegacion_id')->trTable([
            'value' => function ($model) {
                $delegacion = $model->getDelegacion();
                return isset($delegacion->nombre) ? $delegacion->nombre : '';
            },
        ]) ?>
        <?= Html::form($aparato)->multiTrTable([
            'columns' => [
                'tipo',
                'marca',
                'modelo',
                'num_serie',
                'proveedor',
                'fecha_compra',
            ],
        ]); ?>
        <?= Html::form($especifico)->multiTrTable(['exclude' => ['aparato_id']]); ?>
        <?= Html::form($aparato, 'observaciones')->trTable(); ?>
    </table>
</div>

<button class="btn btn-md btn-success float-right" id="export" data-id="<?= $id ?>" data-name="aparato">
    Guardar como PDF
</button>

<?php

Components::footer();
