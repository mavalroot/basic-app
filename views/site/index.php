<?php
session_start();
include_once '../../config/main-local.php';

use models\Aparatos;
use models\Permisos;
use models\Historial;
use models\ActividadReciente;

use utilities\base\Database;

use models\Roles;

use utilities\helpers\validation\Checker;
use utilities\helpers\html\Components;
use utilities\query\QueryBuilder;

$breadcrumps = [
    'Index' => '',
];
$pageTitle = "Index";

Components::header($pageTitle, $breadcrumps);
?>

<h3>Aquí se incluirán links recopilatorios a todas las distintas bases de datos.</h3>

<ul>
    <li>Aparatos</li>
    <li>Tráfico
        <ul>
            <li>Vados</li>
        </ul>
    </li>
</ul>

<?php

$historial = new Historial([
    'tipo' => 'aparatos',
    'referencia' => 2,
    'accion' => 'hola',
    'created_by' => 2
]);
$historial->validate();
var_dump($historial->getErrors());

// $aparato = new Aparatos(['id' => 5]);
// var_dump($aparato->getUsuario());
// $prueba = new ActividadReciente(['id' => 2]);
// $prueba->readOne();
// var_dump($prueba->referencia);
// echo $prueba->getUrl();

// var_dump(Checker::checkPermission('todo'));

// $user = new Roles(['nombre' => $_SESSION['rol']]);
//
// var_dump($user->getRole());
//
// var_dump(Checker::checkPermission('todo'));
//
// var_dump($_SESSION['rol']);
//
// $database = new Database();
// $db = $database->getConnection();

// $try = QueryBuilder::db($db)
// ->select('v.*, s.nombre, s.dni')
// ->from('vados v')
// ->join('terceros s', ['v.tercero_id', 's.id'])
// // ->leftJoin('left table', ['on1', 'on2'])
// ->where(['s.nombre', 'Juan', 'LIKE'])
// // ->andWhere(['where', 'value'])
// // ->groupBy(['v.id', 's.nombre', 's.dni'])
// // ->orderBy('v.tercero_id', 'DESC')
// // ->limit(1)
// ->get();
// // var_dump($try);
// // var_dump($try->fetch(PDO::FETCH_ASSOC));
// // var_dump($try->fetch(PDO::FETCH_ASSOC));

Components::footer();
