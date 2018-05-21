<?php
include_once '../../config/main-local.php';
use models\Permisos;

use utilities\helpers\html\Html;
use utilities\helpers\validation\Errors;

if (!isset($_SESSION)) {
    Errors::forbidden();
}

?>
<fieldset>
    <?= Html::form($rol, 'nombre')->textInput(['message' => 'El nombre de usuario no puede repetirse.']) ?>
    <?= Html::form($rol, 'password_hash')->passwordInput() ?>
    <?= Html::form($rol, 'permiso_id')->selectOption(array_flip(Permisos::getAll()), ['locked' => false]) ?>
</fieldset>

<fieldset>
<div class="form-group text-center">
    <button type="submit" class="btn btn-primary">Enviar</button>
</div>
</fieldset>
