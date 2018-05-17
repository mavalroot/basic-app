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
<?= Html::form($delegacion, 'nombre')->textInput() ?>
</fieldset>

<div class="form-group text-center">
    <button type="submit" class="btn btn-primary">Enviar</button>
</div>
