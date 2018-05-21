<?php
session_start();
include_once '../../config/main-local.php';
use utilities\helpers\validation\Errors;
use utilities\helpers\html\Components;
use controllers\UsuariosController;

// Obtenemos el ID del registro que se va a actualizar.
if (!isset($_GET['id'])) {
    Errors::notFound();
}
$id = $_GET['id'];

$pageTitle = 'Editar usuario';
$breadcrumps = [
    'Inicio' => '../site/index.php',
    'Usuarios' => 'index.php',
    $pageTitle => ''
];
Components::header($pageTitle, $breadcrumps);

$controller = new UsuariosController();
$usuario = $controller->update($id);
?>

<form action="<?= htmlspecialchars($_SERVER["PHP_SELF"] . "?id={$id}");?>" method="post">
    <?php include_once '_form.php' ?>
</form>

<?php
Components::footer();
