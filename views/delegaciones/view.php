<?php
session_start();
include_once '../../config/main-local.php';
use controllers\DelegacionesController;

use utilities\helpers\html\Html;
use utilities\helpers\validation\Errors;
use utilities\helpers\html\Components;

// Se obtiene el ID del registro que se va a mostrar.
if (!isset($_GET['id'])) {
    Errors::notFound();
}
$id = $_GET['id'];
$delegacion = DelegacionesController::view($id);

$pageTitle = $delegacion->nombre;
$breadcrumps = [
    'Inicio' => '../site/index.php',
    'Delegaciones' => 'index.php',
    $pageTitle => ''
];
Components::header($pageTitle, $breadcrumps);


$name = str_replace(' ', '_', $delegacion->nombre);
?>

<div class="row mb-2">
    <div class='col-sm-12 text-right'>
        <button class="btn btn-sm btn-primary" id="export" data-id="<?= $id ?>" data-name="<?= $name ?>">
            <i class="fas fa-download"></i> Guardar como PDF
        </button>


        <a href='create.php' class='btn btn-sm btn-primary'>
            <i class="fas fa-plus"></i> Nueva delegación
        </a>
        <a href='update.php?id=<?= $delegacion->id ?>' class='btn btn-sm btn-primary'>
            <i class="fas fa-edit"></i> Modificar delegación
        </a>

    </div>
</div>

<div id="content" class="table-responsive">
    <table class='table table-striped'>
        <?= Html::form($delegacion)->multiTrTable([
            'exclude' => ['id', 'created_at',]
        ]); ?>
    </table>
</div>

<div class="row">
    <div id="informacion-extra" class="col-sm-12">
        <h4>Aparatos que usa</h4>
        <ul>
            <?php if ($aparatos = $delegacion->getAparatos()): ?>
                <?php foreach ($aparatos as $value): ?>
                    <li><b><?= $value['tipo'] . ':</b> ' . $value['marca'] . ' ' . $value['modelo'] . ' (' . $value['num_serie'] . ')' ?></b>.</li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>
</div>

<?php

Components::footer();
