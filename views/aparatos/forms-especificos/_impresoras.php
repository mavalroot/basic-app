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
    <?= Html::form($especifico, 'cartucho')->label('Cartucho de toner')->textInput() ?>
    <?= Html::form($especifico, 'magenta')->label('Color: magenta')->textInput() ?>
    <?= Html::form($especifico, 'cian')->label('Color: cian')->textInput() ?>
    <?= Html::form($especifico, 'amarillo')->label('Color: amarillo')->textInput() ?>
    <?= Html::form($especifico, 'negro')->label('Color: negro')->textInput() ?>
    <?= Html::form($especifico, 'red')->label('En red')->selectOption(['Sí','No'], ['locked' => false]) ?>

</fieldset>
