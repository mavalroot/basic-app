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
    <div class="col-sm-12 text-right mb-2">
        <a href='create.php' class='btn btn-success float-right'>
            <i class="fas fa-plus"></i> Nuevo ejemplo
        </a>
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
<?php new TableView([
    'model' => $model,
    'query' => $query,
    'rows' => [
        'ejemplo' => ['label' => 'Ejemplo'],
        'created_at' => ['label' => 'Fecha de creación'],
    ],
    'actions' => [
        'replace' => false,
        'add' => [
            [
                'value' => function ($model) {
                    ?>
                    <form method="POST" style="display: inline" name="ejemplo">
                        <button class="btn btn-sm btn-success" type="submit"><i class="fas fa-sync-alt"></i> Ejemplo</button>
                    </form>
                    <?php
                }
            ],
        ]
    ],
]); ?>

<?php else: ?>
    <?= Html::alert('info', 'No ha habido resultados.'); ?>
<?php endif ?>
</div>

<script type="text/javascript">
    ventana('ejemplo', 'ajax/ejemplo.php');
</script>
