<?php
if (file_exists('../../config/main-local.php')) {
    include_once '../../config/main-local.php';
} else {
    include_once '../../../config/main-local.php';
}
use utilities\helpers\html\Html;

?>


<fieldset>
    <legend>Ordenadores</legend>
    <?= Html::form($especifico, 'micro')->label('Microprocesador')->textInput() ?>
    <?= Html::form($especifico, 'memoria')->label('Memoria RAM')->textInput() ?>
    <?= Html::form($especifico, 'tipo_disco')->label('Tipo de disco duro')->textInput() ?>
    <?= Html::form($especifico, 'disco_duro')->label('Disco duro')->textInput() ?>
    <?= Html::form($especifico, 'ip')->label('IP')->textInput() ?>
    <?= Html::form($especifico, 'sist_op')->label('Sistema operativo')->textInput() ?>


</fieldset>
