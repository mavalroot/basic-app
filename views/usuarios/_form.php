<?php
include_once '../../config/main-local.php';
use models\Delegaciones;

use utilities\helpers\html\Html;
use utilities\helpers\validation\Errors;

if (!isset($_SESSION)) {
    Errors::forbidden();
}

?>
<fieldset>
<?= Html::form($usuario, 'nombre')->textInput() ?>
<?= Html::form($usuario, 'delegacion_id')->selectOption(Delegaciones::getAll(true)) ?>
<?= Html::form($usuario, 'extension')->textInput() ?>
</fieldset>

<div class="form-group text-center">
    <button type="submit" class="btn btn-primary">Enviar</button>
</div>
