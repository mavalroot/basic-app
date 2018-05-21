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

$breadcrumps = [
    'Inicio' => '../site/index.php',
    'Aparatos' => 'index.php',
    "Editar aparato$id" => ''
];
$pageTitle = "Actualizar registro";
Components::header($pageTitle, $breadcrumps);

$controller = new AparatosController();
$aparato = $controller->update($id);
?>

<form action="<?= htmlspecialchars($_SERVER["PHP_SELF"] . "?id={$id}");?>" method="post">
    <?php include_once '_form.php' ?>
</form>

<?php
Components::footer();
