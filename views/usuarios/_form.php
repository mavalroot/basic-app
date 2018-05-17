<?php
include_once '../../config/main-local.php';
use utilities\helpers\html\Html;
use utilities\helpers\validation\Errors;

if (!isset($_SESSION)) {
    Errors::forbidden();
}

?>
<fieldset>
<?= Html::form($aparato, 'nombre')->textInput() ?>
<?= Html::form($aparato, 'extension')->textInput() ?>
</fieldset>

<fieldset>
<div class="form-group text-center">
    <button type="submit" class="btn btn-primary">Enviar</button>
</div>
</fieldset>
