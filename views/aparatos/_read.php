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
<?php new TableView([
    'model' => $model,
    'query' => $query,
    'rows' => [
        [
            'label' => 'Usuario y/o delegación utilizándolo',
            'value' => function ($model) {
                $user = $model->getNombreUsuario();
                $delegacion = $model->getNombreDelegacion();
                if ($user != '' && $delegacion != '') {
                    $user .= ', ';
                }
                return $user . $delegacion;
            }
        ],
        'tipo' => ['label' => 'Tipo de aparato'],
        'num_serie' => ['label' => 'Número de serie'],
        'marca' => ['label' => 'Marca'],
        'modelo' => ['label' => 'Modelo']
    ],
    'actions' => [
        'replace' => false,
        'add' => [
            [
                'value' => function ($model) {
                    ?>
                    <form method="POST" style="display: inline" name="cambiar">
                        <input type="hidden" name="id" value="<?= $model->id ?>" />
                        <button class="btn btn-sm btn-success" type="submit"><i class="fas fa-sync-alt"></i> Cambiar usuario</button>
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
    ventana('cambiar', 'ajax/cambiarUsuario.php');
</script>
