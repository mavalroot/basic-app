<?php
include_once '../../config/main-local.php';
use utilities\helpers\html\Html;
use utilities\helpers\html\tableView;
use utilities\helpers\validation\Errors;
use utilities\helpers\html\Components;

if (!isset($_SESSION)) {
    Errors::forbidden();
}

?>

<div class="row">
    <div class="col-sm-12">
        <div class='right-button-margin'>
            <a href='create.php' class='btn btn-success float-right'>
                <i class="fas fa-plus"></i> Nuevo registro
            </a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12 mb-4">
        <?= Components::search($model->getSearchBy(), $searchTerm); ?>
    </div>
</div>

<?php if ($searchTerm): ?>
    <h3>Has buscado <?= $searchTerm ?>:</h3>
<?php endif; ?>

<div id="table-result">
<?php if ($rows > 0): ?>
<?php new tableView([
    'model' => $model,
    'query' => $query,
    'rows' => [
        'nombre' => ['label' => 'Nombre'],
        'dni' => ['label' => 'DNI'],
        'domicilio' => ['label' => 'Domicilio'],
    ],
]); ?>

<?php else: ?>
    <?= Html::alert('info', 'No ha habido resultados.'); ?>
<?php endif ?>
</div>
