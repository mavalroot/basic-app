<?php
session_start();
include_once '../../../config/main-local.php';
?>

<h2>Ejemplo</h2>

<form>
    <div class="form-group">
        Esto es un ejemplo.<br/>
        <input type="text" class="form-contusuario" name="ejemplo" value="ejemplo" />
    </div>
    <div class="form-group text-center mt-2">
        <input type="button" name="ejemplo" value="Enviar"><br />
        <small>(No hace nada)</small>
    </div>
</form>

<script>
$('input').focus();
</script>
