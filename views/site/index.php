<?php
session_start();
include_once '../../config/main-local.php';

use utilities\helpers\html\Components;

// $breadcrumps = [
//     'Inicio' => '',
// ];
$pageTitle = "Inicio";

Components::header($pageTitle, []);
?>

<div class="row">
    <div class="col-sm-3">
        <h4>Aparatos</h4>
        <ul>
            <li>
                <a href="../aparatos/create.php">Crear aparato</a>
            </li>
            <li>
                <a href="../aparatos/index.php">Ver todos</a>
            </li>
            <li>
                <a href="../aparatos/index.php?search=ordenadores&by=tipo">Ver ordenadores</a>
            </li>
            <li>
                <a href="../aparatos/index.php?search=impresoras&by=tipo">Ver impresoras</a>
            </li>
            <li>
                <a href="../aparatos/index.php?search=monitores&by=tipo">Ver monitores</a>
            </li>
            <li>
                <a href="../aparatos/index.php?search=perifericos&by=tipo">Ver periféricos</a>
            </li>
            <li>
                <a href="../aparatos/index.php?search=electronica_red&by=tipo">Ver electrónica de red</a>
            </li>
        </ul>
    </div>
    <div class="col-sm-3">
        <h4>Usuarios</h4>
        <ul>
            <li>
                <a href="../usuarios/create.php">Crear usuario</a>
            </li>
            <li>
                <a href="../usuarios/index.php">Ver todos</a>
            </li>
        </ul>
    </div>
    <div class="col-sm-3">
        <h4>Delegaciones</h4>
        <ul>
            <li>
                <a href="../delegaciones/create.php">Crear delegación</a>
            </li>
            <li>
                <a href="../delegaciones/index.php">Ver todas</a>
            </li>
        </ul>
    </div>
    <div class="col-sm-3">
        <h4>Administración</h4>
        <ul>
            <li>
                <a href="../roles/index.php">Ver roles</a>
            </li>
            <li>
                <a href="../roles/create.php">Crear rol</a>
            </li>
            <li>
                <a href="../historial/index.php">Ver historial</a>
            </li>
        </ul>
    </div>
</div>
<div class="row">
    <div class="col-sm-3">
        <h4>Otros</h4>
        <ul>
            <li>
                <a href="../site/permisos.php">Tabla de permisos</a>
            </li>
        </ul>
    </div>
</div>
<?php

Components::footer();
