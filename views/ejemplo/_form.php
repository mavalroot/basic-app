<?php
include_once '../../config/main-local.php';
use utilities\helpers\html\Html;
use utilities\helpers\validation\Errors;

if (!isset($_SESSION)) {
    Errors::forbidden();
}

?>
<fieldset>
<?= Html::form($inhumacion, 'nombre')->label('Nombre')->textInput() ?>
<?= Html::form($inhumacion, 'dni')->label('DNI')->textInput() ?>
<?= Html::form($inhumacion, 'domicilio')->label('Domicilio')->textInput() ?>
<?= Html::form($inhumacion, 'estado_civil')->label('Estado Civil')->textInput() ?>
<?= Html::form($inhumacion, 'sexo')->label('Sexo')->textInput() ?>
<?= Html::form($inhumacion, 'edad')->label('Edad')->readonlyInput(['message' => '*Este campo es autogenerado.']) ?>
<?= Html::form($inhumacion, 'fecha_nac')->label('Fecha de nacimiento')->dateInput() ?>
<?= Html::form($inhumacion, 'fecha_dec')->label('Fecha de deceso')->dateInput() ?>
<?= Html::form($inhumacion, 'fecha_inh')->label('Fecha de inhumación')->dateInput() ?>
<?= Html::form($inhumacion, 'procedencia')->label('Procedencia')->textInput() ?>
<?= Html::form($inhumacion, 'cert_med_causa')->label('Certificado médico de Causa')->textInput() ?>
<?= Html::form($inhumacion, 'nicho')->label('Nicho')->numberInput() ?>
<?= Html::form($inhumacion, 'fila')->label('Fila')->numberInput() ?>
<?= Html::form($inhumacion, 'patio')->label('Patio')->numberInput() ?>
<?= Html::form($inhumacion, 'bloque')->label('Bloque')->numberInput() ?>
</fieldset>

<fieldset>
<div class="form-group text-center">
    <button type="submit" class="btn btn-primary">Enviar</button>
</div>
</fieldset>
