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
$controller = new UsuariosController();
$usuario = $controller->update($id);

$pageTitle = 'Editar ' . $usuario->nombre;
$breadcrumps = [
    'Index' => '../site/index.php',
    'Usuarios' => 'index.php',
    $pageTitle => ''
];
Components::header($pageTitle, $breadcrumps);

?>

<div class='right-button-margin'>
    <a href='index.php' class='btn btn-primary float-right'>
        <i class="fas fa-list-ul"></i> Consultar todos los registros.
    </a>
</div>

<div class="container">
    <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"] . "?id={$id}");?>" method="post">
        <?php include_once '_form.php' ?>
    </form>
</div>

<?php
Components::footer();
