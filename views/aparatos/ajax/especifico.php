<?php
include_once '../../../config/main-local.php';

use models\Aparatos;

if ($_POST['tipo']) {
    $model = new Aparatos(['tipo' => $_POST['tipo']]);
    $especifico = $model->getModelByType();

    $nombre = '../forms-especificos/_' . $_POST['tipo'] . '.php';
    if (file_exists($nombre)) {
        include_once $nombre;
    }
}
