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
$usuario = UsuariosController::view($id);

$pageTitle = $usuario->nombre;
$breadcrumps = [
    'Inicio' => '../site/index.php',
    'Usuarios' => 'index.php',
    $pageTitle => ''
];
Components::header($pageTitle, $breadcrumps);

$name = str_replace(' ', '_', $usuario->nombre);
?>

<div class="row mb-2">
    <div class='col-sm-12 text-right'>
        <button class="btn btn-sm btn-primary" id="export" data-id="<?= $id ?>" data-name="<?= $name ?>">
            <i class="fas fa-download"></i> Guardar como PDF
        </button>


        <a href='create.php' class='btn btn-sm btn-primary'>
            <i class="fas fa-plus"></i> Nuevo usuario
        </a>
        <a href='update.php?id=<?= $usuario->id ?>' class='btn btn-sm btn-primary'>
            <i class="fas fa-edit"></i> Modificar usuario
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

<div class="row">
    <div id="informacion-extra" class="col-sm-12">
        <h4>Aparatos que usa / ha usado</h4>
        <ul>
            <?php if ($aparatos = $usuario->getAparatosActuales()): ?>
                <?php foreach ($aparatos as $value): ?>
                    <li><b><?= $value['tipo'] . ':</b> ' . $value['marca'] . ' ' . $value['modelo'] . ' (' . $value['num_serie'] . ')' ?></b>. Actualmente.</li>
                <?php endforeach; ?>
            <?php endif; ?>
            <?php if ($aparatos = $usuario->getAparatosAnteriores()): ?>
                <?php foreach ($aparatos as $value): ?>
                    <li><b><?= $value['tipo'] . ': ' . $value['marca'] . ' ' . $value['modelo'] . ' (' . $value['num_serie'] . ')' ?></b>. Hasta <?= date("d-m-Y \a \l\a\s G:i:s", strtotime($value['hasta'])) ?>.</li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>
</div>

<?php

Components::footer();
