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
    <div class='col-sm-12 text-right'>
        <button class="btn btn-sm btn-primary" id="export" data-id="<?= $id ?>" data-name="<?= $name ?>">
            <i class="fas fa-download"></i> Guardar como PDF
        </button>


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
        <h4>Código QR</h4>
        <img src="https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=<?= Html::h('http://'. $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']) ?>&choe=UTF-8" title="Link al aparato" />
        <div id="qrcode" title="Información"></div>
    </div>
</div>

<script type="text/javascript">
    $('#qrcode').qrcode("<?= $aparato->getQRData() ?><?= $especifico->getQRData(['aparato_id']) ?>");
</script>
<?php

Components::footer();
