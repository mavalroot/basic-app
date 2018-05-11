<?php
include_once '../../config/main-local.php';
use utilities\helpers\html\Html;
use utilities\helpers\validation\Errors;

if (!isset($_SESSION)) {
    Errors::forbidden();
}

?>
<fieldset>
<?= Html::form($aparato, 'nombre')->label('Nombre')->textInput() ?>
<?= Html::form($aparato, 'dni')->label('DNI')->textInput() ?>
<?= Html::form($aparato, 'domicilio')->label('Domicilio')->textInput() ?>
<?= Html::form($aparato, 'estado_civil')->label('Estado Civil')->textInput() ?>
<?= Html::form($aparato, 'sexo')->label('Sexo')->textInput() ?>
<?= Html::form($aparato, 'edad')->label('Edad')->readonlyInput(['message' => '*Este campo es autogenerado.']) ?>
<?= Html::form($aparato, 'fecha_nac')->label('Fecha de nacimiento')->dateInput() ?>
<?= Html::form($aparato, 'fecha_dec')->label('Fecha de deceso')->dateInput() ?>
<?= Html::form($aparato, 'fecha_inh')->label('Fecha de aparato')->dateInput() ?>
<?= Html::form($aparato, 'procedencia')->label('Procedencia')->textInput() ?>
<?= Html::form($aparato, 'cert_med_causa')->label('Certificado médico de Causa')->textInput() ?>
<?= Html::form($aparato, 'nicho')->label('Nicho')->numberInput() ?>
<?= Html::form($aparato, 'fila')->label('Fila')->numberInput() ?>
<?= Html::form($aparato, 'patio')->label('Patio')->numberInput() ?>
<?= Html::form($aparato, 'bloque')->label('Bloque')->numberInput() ?>
</fieldset>

<fieldset>
<div class="form-group text-center">
    <button type="submit" class="btn btn-primary">Enviar</button>
</div>
</fieldset>
