<?php
session_start();
include_once '../../config/main-local.php';
use utilities\helpers\html\Html;
use utilities\helpers\html\Components;

Components::header('Error 403 Forbidden', [], false);
Html::alert('danger', 'Usted no tiene permisos para ver este contenido.');
Html::a(['Volver al inicio.', ['../site/index.php']]);

Components::footer(false);
