<?php

session_start();
include_once '../../config/main-local.php';

use utilities\helpers\html\Components;
use controllers\AparatosController;

$breadcrumps = [
    'Index' => '../site/index.php',
    'Aparatos' => 'index.php',
    'Crear aparato' => ''
];
$pageTitle = "Nuevo registro";
Components::header($pageTitle, $breadcrumps);

?>

<div class='right-button-margin'>
    <a href='index.php' class='btn btn-primary float-right'>
        <i class="fas fa-list-ul"></i> Consultar todos los registros
    </a>
</div>

<?php
$controller = new AparatosController();
$crear = $controller->create();
extract($crear);

?>

<form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
    <?php include_once '_form.php' ?>
</form>

<script type="text/javascript">
$('select[name="aparatos[tipo]"]').on('change', function() {
    let eltipo = $(this).children('option:selected').val();
    $.post('ajax/especifico.php', {tipo: eltipo}, function(data) {
        $('#especifico').empty();
        $('#especifico').append(data);
    });
});
</script>

<?php
Components::footer();
