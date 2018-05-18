<?php
include_once '../../../config/main-local.php';

use models\Usuarios;

use controllers\AparatosController;

$controller = new AparatosController;
if ($controller->cambiarUsuario()) {
    return;
};

?>
<div id="option-window">
    <div id="window">
        <div id="close"><i class="fas fa-times"></i></div>
        <h2>Buscar:</h2>
        <form action="" method="post" name="cambiar-usuario">
            <div class="form-group">
                <input type="hidden" name="" value="<?= $_GET['id'] ?>">
                <input class="form-control" list="cambiar">
                <div class="invalid-feedback">
                    <i class="fas fa-exclamation-circle"></i> No se ha elegido una opción válida
                </div>
                <datalist id="cambiar">
                    <?php foreach (Usuarios::getAll() as $key => $value): ?>
                        <option value="<?= $value ?>" data-id="<?= $key ?>">
                    <?php endforeach; ?>
                </datalist>
            </div>
            <div class="form-group text-center mt-2">
                <button type="submit" name="button" class="btn btn-success">Enviar</button>
            </div>
        </form>
    </div>
</div>
<script>
$('input').focus();
cambiarUsuario();
</script>
<?php
