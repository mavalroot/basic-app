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
<p>Tabla para consultar la accesibilidad de los permisos.<br/>
Tu permiso actual es: <b><?= Permisos::getPermisoNombre($_SESSION['permiso_id']) ?></b>.</p>

<h3>Aparatos</h3>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <th>Rol</th>
            <th>Index</th>
            <th>View</th>
            <th>Create</th>
            <th>Update</th>
            <th>Delete</th>
            <th>Cambiar Usuario</th>
        </thead>
        <tbody>
            <tr>
                <td>Admin</td>
                <td>Sí</td>
                <td>Sí</td>
                <td>Sí</td>
                <td>Sí</td>
                <td>Sí</td>
                <td>Sí</td>
            </tr>
            <tr>
                <td>Normal</td>
                <td>Sí</td>
                <td>Sí</td>
                <td>Sí</td>
                <td>Sí</td>
                <td>Sí</td>
                <td>Sí</td>
            </tr>
        </tbody>
    </table>
</div>

<h3>Usuarios</h3>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <th>Rol</th>
            <th>Index</th>
            <th>View</th>
            <th>Create</th>
            <th>Update</th>
            <th>Delete</th>
        </thead>
        <tbody>
            <tr>
                <td>Admin</td>
                <td>Sí</td>
                <td>Sí</td>
                <td>Sí</td>
                <td>Sí</td>
                <td>Sí</td>
            </tr>
            <tr>
                <td>Normal</td>
                <td>Sí</td>
                <td>Sí</td>
                <td>Sí</td>
                <td>Sí</td>
                <td>Sí</td>
            </tr>
        </tbody>
    </table>
</div>

<h3>Delegaciones</h3>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <th>Rol</th>
            <th>Index</th>
            <th>View</th>
            <th>Create</th>
            <th>Update</th>
            <th>Delete</th>
        </thead>
        <tbody>
            <tr>
                <td>Admin</td>
                <td>Sí</td>
                <td>Sí</td>
                <td>Sí</td>
                <td>Sí</td>
                <td>Sí</td>
            </tr>
            <tr>
                <td>Normal</td>
                <td>Sí</td>
                <td>Sí</td>
                <td>Sí</td>
                <td>Sí</td>
                <td>Sí</td>
            </tr>
        </tbody>
    </table>
</div>

<h3>Roles</h3>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <th>Rol</th>
            <th>Index</th>
            <th>View</th>
            <th>Create</th>
            <th>Update</th>
            <th>Delete</th>
        </thead>
        <tbody>
            <tr>
                <td>Admin</td>
                <td>Sí</td>
                <td>Sí</td>
                <td>Sí</td>
                <td>Sí</td>
                <td>Sí</td>
            </tr>
            <tr>
                <td>Normal</td>
                <td>No</td>
                <td>No</td>
                <td>No</td>
                <td>No</td>
                <td>No</td>
            </tr>
        </tbody>
    </table>
</div>

<h3>Historial</h3>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <th>Rol</th>
            <th>Index</th>
        </thead>
        <tbody>
            <tr>
                <td>Admin</td>
                <td>Sí</td>
            </tr>
            <tr>
                <td>Normal</td>
                <td>No</td>
            </tr>
        </tbody>
    </table>
</div>
<?php

Components::footer();
