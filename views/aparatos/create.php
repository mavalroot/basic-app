<?php
session_start();
include_once '../../config/main-local.php';

use utilities\helpers\html\Components;
use controllers\AparatosController;

$pageTitle = 'Crear nuevo aparato';
$breadcrumps = [
    'Inicio' => '../site/index.php',
    'Aparatos' => 'index.php',
    $pageTitle => ''
];
Components::header($pageTitle, $breadcrumps);

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
