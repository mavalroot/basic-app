<?php
include_once '../../config/main-local.php';
use utilities\helpers\html\Html;
use utilities\helpers\html\TableView;
use utilities\helpers\validation\Errors;
use utilities\helpers\html\Components;

if (!isset($_SESSION)) {
    Errors::forbidden();
}

?>

<div class="row">
    <div class="col-sm-12 mb-4">
        <?= Components::search($model->getSearchBy(), $searchTerm); ?>
    </div>
</div>

<div id="table-result">
<?php if ($searchTerm): ?>
    <h3>Has buscado <?= $searchTerm ?>:</h3>
<?php endif; ?>

<?php if ($rows > 0): ?>
<?php new TableView([
    'model' => $model,
    'query' => $query,
    'rows' => [
        [
            'raw' => true,
            'label' => 'Acción',
            'value' => function ($model) {
                return $model->getAction();
            }
        ]
    ],
    'actions' => false
]); ?>

<?php else: ?>
    <?= Html::alert('info', 'No ha habido resultados.'); ?>
<?php endif ?>
</div>
