<?php
session_start();
include_once '../../config/main-local.php';

use models\Permisos;

use utilities\helpers\html\Components;

// $breadcrumps = [
//     'Inicio' => '',
// ];
$pageTitle = "Tabla de permisos";

Components::header($pageTitle, []);
?>
<p>
    Tabla para consultar la accesibilidad de los permisos.<br/>
    Tu permiso actual es: <b><?= Permisos::getPermisoNombre($_SESSION['permiso_id']) ?></b>.
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
            <tr>
                <th>ADMIN</th>
                <td>ver, crear, modificar, borrar</td>
                <td>ver, crear, modificar, borrar</td>
                <td>ver, crear, modificar, borrar</td>
                <td>ver, crear, modificar, borrar</td>
                <td>ver</td>
            </tr>
            <tr>
                <th>NORMAL</th>
                <td>ver, crear, modificar, borrar</td>
                <td>ver, crear, modificar, borrar</td>
                <td>ver, crear, modificar, borrar</td>
                <td>---</td>
                <td>---</td>
            </tr>
            <tr>
                <th>LECTOR</th>
                <td>ver</td>
                <td>ver</td>
                <td>ver</td>
                <td>---</td>
                <td>---</td>
            </tr>
            <tr>
                <th>EDITOR</th>
                <td>ver, crear, modificar</td>
                <td>ver, crear, modificar</td>
                <td>ver, crear, modificar</td>
                <td>---</td>
                <td>---</td>
            </tr>
        </tbody>
    </table>
</div>
<?php

Components::footer();
