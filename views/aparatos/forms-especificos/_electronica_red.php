<?php
if (file_exists('../../config/main-local.php')) {
    include_once '../../config/main-local.php';
} else {
    include_once '../../../config/main-local.php';
}
use utilities\helpers\html\Html;

?>


<fieldset>
    <legend>Electrónica de red</legend>
    <?= Html::form($especifico, 'ubicacion')->textInput() ?>
    <?= Html::form($especifico, 'tipo')->textInput() ?>
    <?= Html::form($especifico, 'descripcion')->textarea() ?>

</fieldset>
