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
$result = AparatosController::view($id);
extract($result);
$name = $aparato->getFullName();
$pageTitle = $name;

$breadcrumps = [
    'Inicio' => '../site/index.php',
    'Aparatos' => 'index.php',
    $name => ''
];
Components::header($pageTitle, $breadcrumps);

$name = str_replace(' ', '_', $name); ?>

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
            <i class="fas fa-plus"></i> Nuevo aparato
        </a>
        <a href='update.php?id=<?= $aparato->id ?>' class='btn btn-sm btn-primary'>
            <i class="fas fa-edit"></i> Modificar aparato
        </a>

    </div>
</div>

<div id="content" class="table-responsive">
    <table class='table table-striped'>
        <?= Html::form($aparato, 'usuario_id')->trTable([
            'value' => function ($model) {
                return $model->getNombreUsuario();
            },
        ]) ?>
        <?= Html::form($aparato, 'delegacion_id')->trTable([
            'value' => function ($model) {
                return $model->getNombreDelegacion();
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
<div class="row">
    <div class="col-sm-6" id="informacion-extra">
        <h4>Lo usaron anteriormente</h4>
        <ul>
            <?php if ($usuarios = $aparato->getUsuariosAnteriores()): ?>
                <?php foreach ($usuarios as $value): ?>
                    <li><b><?= $value['nombre'] ?></b>. Hasta <?= date("d-m-Y \a \l\a\s G:i:s", strtotime($value['created_at'])) ?>.</li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>

    <div class="col-sm-6">
        <div id="printable-qrcode" style="background: white; max-width: 400px;">
            <div style="display: flex; align-items: center;">
                <img src="http://inventario.local/libs/images/ayto.png" style="height: 80px;" alt=""> <h3>Ayuntamiento de Chipiona</h3>
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

<script type="text/javascript">
    $('#qrcode').qrcode({width: 80, height: 80, text: 'http://192.168.0.103/qr/view.php?id=<?= $aparato->id ?>'});
</script>
<?php

Components::footer();
