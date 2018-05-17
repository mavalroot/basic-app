<?php
session_start();
include_once '../../config/main-local.php';
use models\ActividadReciente;

use utilities\helpers\html\Html;
use utilities\helpers\html\tableView;
use utilities\helpers\validation\Errors;
use utilities\helpers\html\Components;
use controllers\RolesController;

// Se obtiene el ID del registro que se va a mostrar.
if (!isset($_GET['id'])) {
    Errors::notFound();
}
$id = $_GET['id'];
$rol = RolesController::view($id);

$breadcrumps = [
    'Index' => '../site/index.php',
    'Roles' => 'index.php',
    $rol->nombre => ''
];
$pageTitle = $rol->nombre;
Components::header($pageTitle, $breadcrumps);

?>

<div class='right-button-margin'>
    <a href='index.php' class='btn btn-primary float-right'>
        <i class="fas fa-list-ul"></i> Consultar todos los roles
    </a>
</div>

<div class="table-responsive">
    <table class='table table-striped'>
        <?= Html::form($rol)->multiTrTable([
            'columns' => ['nombre', 'last_con']
        ]); ?>
        <?= Html::form($rol, 'permiso')->trTable([
            'value' => function ($model) {
                return $model->getPermiso();
            },
        ]) ?>
    </table>
</div>
<hr class="style11">
<div class="row-fluid">
    <h3>Actividad reciente</h3>
    <?php new tableView([
        'model' => new ActividadReciente(),
        'query' => $rol->getActividad([
            'limit' => 10,
        ]),
        'rows' => [
            [
                'label' => 'Historial',
                'raw' => true,
                'value' => function ($model) {
                    return '<small>[' . $model->created_at . ']</small> <b>' . $model->created_by . '</b>: ' . $model->accion;
                },
            ]
        ],
        'actions' => [
            'replace' => true,
            'add' => [
                [
                    'value' => function ($model) {
                        return $model->getUrl();
                    },
                ],
            ]
        ],
    ]); ?>

</div>

<?php

Components::footer();
