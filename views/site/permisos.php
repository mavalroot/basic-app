<?php
session_start();
include_once '../../config/main-local.php';

use models\Permisos;

use utilities\helpers\html\Components;

$pageTitle = "Tabla de permisos";
$breadcrumps = [
    'Inicio' => '../site/index.php',
    $pageTitle => ''
];

Components::header($pageTitle, $breadcrumps);
$miPermiso = Permisos::getPermisoNombre($_SESSION['permiso_id']);
?>
<p>
    Tabla para consultar la accesibilidad de los permisos.<br/>
    Tu permiso actual es: <b><?= $miPermiso ?></b>.
</p>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <th>Permiso</th>
            <th>Aparatos</th>
            <th>Usuarios</th>
            <th>Delegaciones</th>
            <th>Roles</th>
            <th>Historial</th>
        </thead>
        <tbody>
            <tr <?= $miPermiso == Permisos::ADMIN ? 'class="bg-warning"' : '' ?>>
                <th>ADMIN</th>
                <td>ver, crear, modificar, borrar</td>
                <td>ver, crear, modificar, borrar</td>
                <td>ver, crear, modificar, borrar</td>
                <td>ver, crear, modificar, borrar</td>
                <td>ver</td>
            </tr>
            <tr <?= $miPermiso == Permisos::NORMAL ? 'class="bg-warning"' : '' ?>>
                <th>NORMAL</th>
                <td>ver, crear, modificar, borrar</td>
                <td>ver, crear, modificar, borrar</td>
                <td>ver, crear, modificar, borrar</td>
                <td>---</td>
                <td>---</td>
            </tr>
            <tr <?= $miPermiso == Permisos::EDITOR ? 'class="bg-warning"' : '' ?>>
                <th>EDITOR</th>
                <td>ver, crear, modificar</td>
                <td>ver, crear, modificar</td>
                <td>ver, crear, modificar</td>
                <td>---</td>
                <td>---</td>
            </tr>
            <tr <?= $miPermiso == Permisos::LECTOR ? 'class="bg-warning"' : '' ?>>
                <th>LECTOR</th>
                <td>ver</td>
                <td>ver</td>
                <td>ver</td>
                <td>---</td>
                <td>---</td>
            </tr>
        </tbody>
    </table>
</div>
<?php

Components::footer();
