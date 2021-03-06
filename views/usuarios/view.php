<?php
session_start();
include_once '../../config/main-local.php';
use models\Historial;

use utilities\helpers\html\Html;
use utilities\helpers\html\TableView;
use utilities\helpers\validation\Errors;
use utilities\helpers\html\Components;
use controllers\UsuariosController;

// Se obtiene el ID del registro que se va a mostrar.
if (!isset($_GET['id'])) {
    Errors::notFound();
}
$id = $_GET['id'];
$usuario = UsuariosController::view($id);

$breadcrumps = [
    'Inicio' => '../site/index.php',
    'Usuarios' => 'index.php',
    $usuario->nombre => ''
];
$pageTitle = $usuario->nombre;
Components::header($pageTitle, $breadcrumps);

?>

<div class="row mb-2">
    <div class='col-sm-12 text-right'>
        <a href='create.php' class='btn btn-sm btn-primary'>
            <i class="fas fa-plus"></i> Nuevo usuario
        </a>
        <a href='update.php?id=<?= $usuario->id ?>' class='btn btn-sm btn-primary'>
            <i class="fas fa-edit"></i> Modificar usuario
        </a>

    </div>
</div>

<div class="table-responsive">
    <table class='table table-striped'>
        <?= Html::form($usuario)->multiTrTable([
            'columns' => ['nombre', 'last_con']
        ]); ?>
        <?= Html::form($usuario, 'permiso_id')->trTable([
            'value' => function ($model) {
                return $model->getPermiso();
            },
        ]) ?>
    </table>
</div>
<hr class="style11">
<div class="row-fluid">
    <h3>Actividad reciente (10 últimos)</h3>
    <?php new TableView([
        'model' => new Historial(),
        'query' => $usuario->getHistorial([
            'limit' => 10,
        ]),
        'rows' => [
            [
                'raw' => true,
                'label' => 'Acción',
                'value' => function ($model) {
                    return $model->getAction();
                }
            ]
        ],
        'actions' => false,
    ]); ?>

</div>

<?php

Components::footer();
