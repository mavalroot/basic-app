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

<?= Html::form($aparato, 'usuario_id')->label('Usuario')->selectOption($aparato->getUsuarios(true)) ?>
<?= Html::form($aparato, 'delegacion_id')->label('Delegación')->selectOption($aparato->getDelegaciones(true)) ?>
<?= Html::form($aparato, 'tipo')->label('Tipo de aparato')->selectOption($aparato->getTypes(), [
        'select' => true
    ]) ?>
<?= Html::form($aparato, 'marca')->label('Marca')->textInput() ?>
<?= Html::form($aparato, 'modelo')->label('Modelo')->textInput() ?>
<?= Html::form($aparato, 'num_serie')->label('Número de serie')->textInput() ?>
<?= Html::form($aparato, 'fecha_compra')->label('Fecha de compra')->dateInput() ?>
<?= Html::form($aparato, 'proveedor')->label('Proveedor')->textInput() ?>
<?= Html::form($aparato, 'observaciones')->label('Observaciones')->textarea() ?>
</fieldset>

<div id="especifico">
    <?php if (isset($aparato->tipo) &&  file_exists('forms-especificos/_' . $aparato->tipo . '.php')): ?>
        <?php include_once 'forms-especificos/_' . $aparato->tipo . '.php' ?>
    <?php endif; ?>
</div>

<fieldset>
<div class="form-group text-center">
    <button type="submit" class="btn btn-primary">Enviar</button>
</div>
</fieldset>

<script type="text/javascript">
$('select[name="aparatos[tipo]"]').on('change', function() {
    let eltipo = $(this).children('option:selected').val();
    $.post('ajax/especifico.php', {tipo: eltipo}, function(data) {
        $('#especifico').empty();
        $('#especifico').append(data);
        // $("#especifico").load(location.href+" #especifico>*","");
        // $('#especifico').append(data);
    });
});
</script>
