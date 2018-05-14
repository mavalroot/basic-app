<?php
if (file_exists('../../config/main-local.php')) {
    include_once '../../config/main-local.php';
} else {
    include_once '../../../config/main-local.php';
}
use utilities\helpers\html\Html;

?>


<fieldset>
    <legend>Periféricos</legend>
    <?= Html::form($especifico, 'descripcion')->label('Descripción')->textarea() ?>
</fieldset>
