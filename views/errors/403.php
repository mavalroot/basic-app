<?php
session_start();
include_once '../../config/main-local.php';
use utilities\helpers\html\Html;
use utilities\helpers\html\Components;

Components::header('Error 403 Forbidden');
Html::alert('danger', 'Usted no tiene permisos para ver este contenido.');

Components::footer();
