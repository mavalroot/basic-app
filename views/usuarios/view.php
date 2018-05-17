<?php
session_start();
include_once '../../config/main-local.php';
use controllers\UsuariosController;

use utilities\helpers\html\Html;
use utilities\helpers\validation\Errors;
use utilities\helpers\html\Components;

// Se obtiene el ID del registro que se va a mostrar.
if (!isset($_GET['id'])) {
    Errors::notFound();
}
$id = $_GET['id'];

$pageTitle = "Consultar un registro";
$breadcrumps = [
    'Index' => '../site/index.php',
    'Aparatos' => 'index.php',
    "Detalle aparato$id" => ''
];
Components::header($pageTitle, $breadcrumps);

$usuario = UsuariosController::view($id);

$name = str_replace(' ', '_', $usuario->nombre);
?>

<div class="row mb-2">
    <div class="col-sm-6">
        <button class="btn btn-md btn-success" id="export" data-id="<?= $id ?>" data-name="<?= $name ?>">
            Guardar como PDF
        </button>
    </div>

<div class='col-sm-6'>
    <a href='index.php' class='btn btn-primary float-right'>
        <i class="fas fa-list-ul"></i> Consultar todos los registros
    </a>
</div>
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

<?php

Components::footer();
