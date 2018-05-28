<?php
include_once '../../config/main-local.php';
use models\Ejemplo;

use utilities\helpers\html\Html;
use utilities\helpers\validation\Errors;

if (!isset($_SESSION)) {
    Errors::forbidden();
}

?>

<div class="row">
    <div class="col-sm-12 text-right mb-2">
        <a href='index.php' class='btn btn-danger'>
            <i class="fas fa-trash-alt"></i> Descartar
        </a>
    </div>
</div>

<fieldset>
<?= Html::form($ejemplo, 'ejemplo')->textInput() ?>
</fieldset>

<div class="form-group text-center">
    <button type="submit" class="btn btn-primary">Enviar</button>
</div>
