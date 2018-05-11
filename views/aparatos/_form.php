<?php
include_once '../../config/main-local.php';
use utilities\helpers\html\Html;
use utilities\helpers\validation\Errors;

if (!isset($_SESSION)) {
    Errors::forbidden();
}

?>
<fieldset>
    <legend>General</legend>
<?= Html::form($aparato, 'usuario_id')->label('Usuario')->textInput() ?>
<?= Html::form($aparato, 'delegacion_id')->label('Delegación')->textInput() ?>
<?= Html::form($aparato, 'tipo')->label('Tipo de aparato')->textInput() ?>
<?= Html::form($aparato, 'marca')->label('Marca')->textInput() ?>
<?= Html::form($aparato, 'modelo')->label('Modelo')->textInput() ?>
<?= Html::form($aparato, 'num_serie')->label('Número de serie')->numberInput() ?>
<?= Html::form($aparato, 'fecha_compra')->label('Fecha de compra')->dateInput() ?>
<?= Html::form($aparato, 'proveedor')->label('Proveedor')->textInput() ?>
<?= Html::form($aparato, 'observaciones')->label('Observaciones')->textInput() ?>
</fieldset>

<fieldset>
<div class="form-group text-center">
    <button type="submit" class="btn btn-primary">Enviar</button>
</div>
</fieldset>
