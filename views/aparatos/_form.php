<?php
include_once '../../config/main-local.php';
use models\Usuarios;
use models\Delegaciones;

use utilities\helpers\html\Html;
use utilities\helpers\validation\Errors;

if (!isset($_SESSION)) {
    Errors::forbidden();
}
?>

<div class="row">
    <div class="col-sm-12 text-right mb-2">
        <a href='index.php' class='btn btn-danger'>
            <i class="fas fa-trash-alt"></i> Descartar
        </a>
    </div>
</div>

<fieldset>
    <legend>General</legend>

<?= Html::form($aparato, 'usuario_id')->selectOption(Usuarios::getAll(true)) ?>
<?= Html::form($aparato, 'delegacion_id')->selectOption(Delegaciones::getAll(true)) ?>
<?= Html::form($aparato, 'tipo')->selectOption($aparato->getTypes(), [
        'select' => true
    ]) ?>
<?= Html::form($aparato, 'marca')->textInput() ?>
<?= Html::form($aparato, 'modelo')->textInput() ?>
<?= Html::form($aparato, 'num_serie')->textInput() ?>
<?= Html::form($aparato, 'fecha_compra')->dateInput() ?>
<?= Html::form($aparato, 'proveedor')->textInput() ?>
<?= Html::form($aparato, 'observaciones')->textarea() ?>
</fieldset>

<div id="especifico">
    <?php if (isset($aparato->tipo) &&  file_exists('forms-especificos/_' . $aparato->tipo . '.php')): ?>
        <?php include_once 'forms-especificos/_' . $aparato->tipo . '.php' ?>
    <?php endif; ?>
</div>

<div class="form-group text-center">
    <button type="submit" class="btn btn-primary">Enviar</button>
</div>
