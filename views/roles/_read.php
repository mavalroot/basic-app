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
    <div class="col-sm-12 mb-4">
        <?= Components::search($model->getSearchBy(), $searchTerm); ?>
    </div>
</div>

<?php if ($searchTerm): ?>
    <h3>Has buscado <?= $searchTerm ?>:</h3>
<?php endif; ?>

<?php if ($rows > 0): ?>
<?php new tableView([
    'model' => $model,
    'query' => $query,
    'rows' => [
        'nombre' => ['label' => 'Nombre de rol'],
        [
            'label' => 'permiso',
            'value' => function ($model) {
                return $model->getPermiso();
            }
        ],
        'last_con' => ['label' => 'Última conexión'],
    ],
    'actions' => [
        'replace' => true,
        'add' => [
            [
                'value' => function ($model) {
                    return Html::a(['<i class="fas fa-eye"></i> Ver', ['view.php', 'id' => $model->id], ['class' => 'btn btn-info btn-sm']], true);
                },
            ],
        ]
    ],
]); ?>

<?php else: ?>
    <?= Html::alert('info', 'No ha habido resultados.'); ?>
<?php endif ?>
