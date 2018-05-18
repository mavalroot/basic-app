<?php
session_start();
include_once '../../config/main-local.php';
use utilities\helpers\validation\Errors;
use utilities\helpers\html\Components;
use controllers\AparatosController;

// Obtenemos el ID del registro que se va a actualizar.
if (!isset($_GET['id'])) {
    Errors::notFound();
}
$id = $_GET['id'];

$pageTitle = 'Editar aparato';
$breadcrumps = [
    'Index' => '../site/index.php',
    'Aparatos' => 'index.php',
    $pageTitle => ''
];
Components::header($pageTitle, $breadcrumps);

$controller = new AparatosController();
$result = $controller->update($id);
extract($result);
?>

<div class='right-button-margin'>
    <a href='index.php' class='btn btn-primary float-right'>
        <i class="fas fa-list-ul"></i> Consultar todos los registros.
    </a>
</div>

<?php
?>

<form action="<?= htmlspecialchars($_SERVER["PHP_SELF"] . "?id={$id}");?>" method="post">
    <?php include_once '_form.php' ?>
</form>

<?php
Components::footer();
