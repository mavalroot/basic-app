<?php
session_start();
include_once '../../config/main-local.php';
use utilities\helpers\html\Html;
use utilities\helpers\html\Components;

Components::header('Error 404 Not found');
Html::alert('danger', 'No se pudo encontrar la página.');

Components::footer();
