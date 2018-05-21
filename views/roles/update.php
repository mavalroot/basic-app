<?php
session_start();
include_once '../../config/main-local.php';
use utilities\helpers\validation\Errors;
use utilities\helpers\html\Components;
use controllers\RolesController;

// Obtenemos el ID del registro que se va a actualizar.
if (!isset($_GET['id'])) {
    Errors::notFound();
}
$id = $_GET['id'];

$breadcrumps = [
    'Inicio' => '../site/index.php',
    'Aparatos' => 'index.php',
    "Editar aparato$id" => ''
];
$pageTitle = "Actualizar registro";
Components::header($pageTitle, $breadcrumps);

?>

<div class='right-button-margin'>
    <a href='index.php' class='btn btn-primary float-right'>
        <i class="fas fa-list-ul"></i> Consultar todos los registros.
    </a>
</div>

<?php
    $controller = new RolesController();
    $rol = $controller->update($id);
?>

<form action="<?= htmlspecialchars($_SERVER["PHP_SELF"] . "?id={$id}");?>" method="post">
    <?php include_once '_form.php' ?>
</form>

<?php
Components::footer();
