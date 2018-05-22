<?php
session_start();
include_once '../../config/main-local.php';
use utilities\helpers\html\Html;
use utilities\helpers\html\Components;

Components::header('Error 404 Not found', [], false);
Html::alert('danger', 'No se pudo encontrar la página.');
Html::a(['Volver al inicio.', ['../site/index.php']]);

Components::footer(false);
