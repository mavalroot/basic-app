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
    <?= Html::form($especifico, 'micro')->textInput() ?>
    <?= Html::form($especifico, 'memoria')->textInput() ?>
    <?= Html::form($especifico, 'tipo_disco')->textInput() ?>
    <?= Html::form($especifico, 'disco_duro')->textInput() ?>
    <?= Html::form($especifico, 'ip')->textInput() ?>
    <?= Html::form($especifico, 'sist_op')->textInput() ?>


</fieldset>
