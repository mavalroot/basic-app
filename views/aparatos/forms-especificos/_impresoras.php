<?php
if (file_exists('../../config/main-local.php')) {
    include_once '../../config/main-local.php';
} else {
    include_once '../../../config/main-local.php';
}
use utilities\helpers\html\Html;

?>

<fieldset>
    <legend>Impresoras</legend>
    <?= Html::form($especifico, 'cartucho')->textInput() ?>
    <?= Html::form($especifico, 'magenta')->textInput() ?>
    <?= Html::form($especifico, 'cian')->textInput() ?>
    <?= Html::form($especifico, 'amarillo')->textInput() ?>
    <?= Html::form($especifico, 'negro')->textInput() ?>
    <?= Html::form($especifico, 'red')->selectOption(['Sí','No'], ['locked' => false]) ?>

</fieldset>
