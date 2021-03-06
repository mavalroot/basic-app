<?php
session_start();
include_once '../../config/main-local.php';

use models\Permisos;

use utilities\helpers\html\Components;

use utilities\helpers\validation\Checker;

// $breadcrumps = [
//     'Inicio' => '',
// ];
$pageTitle = "Inicio";

Components::header($pageTitle, []);
?>
<?php if (Checker::checkPermission(Permisos::QRONLY)): ?>
    <h2>Estás como invitado y no puedes acceder a todo el contenido.</h2>
<?php else: ?>
    <div class="row">
        <div class="col-sm-6">
            <h4>Ejemplos</h4>
            <ul>
                <li>
                    <a href="../ejemplo/create.php">Crear ejemplo</a>
                </li>
                <li>
                    <a href="../ejemplo/index.php">Ver todos</a>
                </li>
            </ul>
        </div>
        <div class="col-sm-6">
            <h4>Administración</h4>
            <ul>
                <li>
                    <a href="../usuarios/index.php">Ver usuarios</a>
                </li>
                <li>
                    <a href="../usuarios/create.php">Crear usuario</a>
                </li>
                <li>
                    <a href="../historial/index.php">Ver historial</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <h4>Otros</h4>
            <ul>
                <li>
                    <a href="../site/permisos.php">Tabla de permisos</a>
                </li>
            </ul>
        </div>
    </div>
<?php endif;

Components::footer();
