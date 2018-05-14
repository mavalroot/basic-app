<?php
include_once '../../../config/main-local.php';
use utilities\helpers\html\Html;

?>


<fieldset>
    <legend>Electrónica de red</legend>
    <?= Html::form($especifico, 'ubicacion')->label('Ubicación')->textInput() ?>
    <?= Html::form($especifico, 'tipo')->label('Tipo')->textInput() ?>
    <?= Html::form($especifico, 'descripcion')->label('Descripción')->textarea() ?>

</fieldset>
