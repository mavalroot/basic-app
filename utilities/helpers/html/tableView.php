<?php

namespace utilities\helpers\html;

use PDO;
use utilities\helpers\html\Html;

/**
 * @author María Valderrama Rodríguez <contact@mavalroot.es>
 * @copyright Copyright (c) 2018, María Valderrama Rodríguez
 *
 * Clase que ayuda en la construcción de tablas.
 */
class TableView
{
    /**
     * Constructor de la clase.
     * @param   array   $config Configuración. Variará según $mode. Para ver el
     * formato detallado ver cada función individualmente.
     *
     * @param   string  $mode   Indica qué tipo va a recibirse. Pueden ser:
     * "full", "row" o "head". Por defecto es 'full'.
     * También admite valores numéricos como en un array, 0, 1 ó 2.
     */
    public function __construct($config, $mode = 'full')
    {
        if (is_string($mode)) {
            $mode = strtolower($mode);
        }

        switch ($mode) {
            case 'full':
            case '0':
            default:
            return $this->fullTable($config);
            break;
            case 'row':
            case '1':
            return $this->singleRow($config);
            break;
            case 'head':
            case '2':
            return $this->tableHeader($config);
        }
    }

    /**
     * Inserta una tabla completa.
     * @param  array $config Configuración. Sigue el siguiente formato:
     * [
     *     'model' => $model,
     *     'query' => $query,
     *     'rows' => [
     *         'columna1' => ['label' => 'Columna1'],
     *         'columna1' => ['label' => 'Columna2'],
     *         [    // Columna customizada.
     *             'label' => 'Custom',
     *             'value' => function ($model) {
     *                  // Contenido de la columna...
     *             },
     *            'raw' => false,
     *         ],
     *     ],
     *     'actions' => [
     *          'replace' => false,     // Indica si reemplaza los botones estandar
     *                                  // Ver, editar y borrar.
     *          'add' => [
     *          [
     *              'value' => function ($model) {
     *                  // Añade aquí el botón...
     *              }
     *          ],
     *     ],
     * ]
     */
    public function fullTable($config)
    {
        $actions = null;
        extract($config); ?>
        <div class="table-responsive">
            <table class='table table-striped'>
                <?= $this->tableHeader($config) ?>
                <tbody>
                <?php while ($row = $query->fetch(PDO::FETCH_ASSOC)): ?>
                    <?php $model->readOne(['id', $row['id']]); ?>
                    <?= $this->singleRow([
                        'model' => $model,
                        'actions' => $actions,
                        'rows' => $rows,
                    ]) ?>
                <?php endwhile ?>
                </tbody>
            </table>
        </div>
        <?php
    }

    /**
     * Inserta una sola línea de tabla.
     * @param  array $config Configuración. Sigue el siguiente formato:
     *
     * [
     *      'model' => $model,
     *      'actions' => [
     *          'replace' => false,     // Indica si reemplaza los botones estandar
     *                                  // Ver, editar y borrar.
     *          'add' => [
     *          [
     *              'value' => function ($model) {
     *                  // Añade aquí el botón...
     *              }
     *          ],
     *       ]
     *      'rows' => [
     *          'columna1' => ['label' => 'Columna1'],
     *          'columna1' => ['label' => 'Columna2'],
     *          [
     *               'label' => 'Columna1',
     *               'value' => function($model) {
     *                   return
     *               }
     *          ]
     *      ],
     * ];
     *
     */
    public function singleRow($config)
    {
        $actions = null;
        extract($config); ?>
        <tr id="row_<?= $model->id ?>">
            <?php foreach ($rows as $key => $value): ?>
                <?php $value['raw'] = isset($value['raw']) ? $value['raw'] : false ; ?>
                <?php if (is_integer($key)): ?>
                    <td><?= $value['raw'] ? call_user_func($value['value'], $model) : Html::h(call_user_func($value['value'], $model)) ?></td>
                <?php else: ?>
                    <td><?= isset($model->$key) ? Html::h($model->$key) : '' ?></td>
                <?php endif; ?>
            <?php endforeach; ?>
            <?= $this->singleButtons([
                    'model' => $model,
                    'actions' => $actions,
                    'id' => $model->id,
            ]) ?>
        </tr>
        <?php
    }

    /**
     * Inserta los botones comunes de ver, editar y borrar.
     * @param  array $config Configuración. Sigue el siguiente formato:
     * [
     *      'model' => $model,
     *      'actions' => [
     *          'replace' => false,     // Indica si reemplaza los botones estandar
     *                                  // Ver, editar y borrar.
     *          'add' => [
     *          [
     *              'value' => function ($model) {
     *                  // Añade aquí el botón...
     *              }
     *          ],
     *       ]
     *      'id' => $id,
     * ];
     */
    public function singleButtons($config)
    {
        extract($config); ?>
        <td class="text-center">
        <?php
        if (isset($actions) && $actions) {
            foreach ($actions['add'] as $value) {
                call_user_func($value['value'], $model);
            }
        }
        if ($actions !== false && (!isset($actions) || $actions['replace'] == false)) {
            Html::a(['<i class="fas fa-eye"></i> Ver', ['view.php', 'id' => Html::h($id)], ['class' => 'btn btn-info btn-sm']], true);

            Html::a(['<i class="fas fa-edit"></i> Editar', ['update.php', 'id' => Html::h($id)], ['class' => 'btn btn-primary btn-sm']], true);

            Html::a(['<i class="fas fa-trash-alt"></i> Borrar', ['#'], ['class' => 'btn btn-sm btn-danger delete-object', 'attr' => ['delete-id' =>  Html::h($id)]]], true);
        } ?>
        </td>
        <?php
    }

    /**
     * Inserta el theader de una tabla.
     * @param  array $config Configuración. Sigue el siguiente formato:
     *
     * [
     *      'rows' => [
     *          'columna1' => ['label' => 'Columna1'],
     *          'columna1' => ['label' => 'Columna2'],
     *      ]
     * ];
     */
    public function tableHeader($config)
    {
        extract($config); ?>
        <thead>
            <tr>
                <?php foreach ($rows as $value): ?>
                    <th scope="col"><?= $value['label'] ?></th>
                <?php endforeach; ?>
                <?php if (!isset($actions) || $actions !== false): ?>
                    <th scope="col" class="text-center">Acciones</th>
                <?php endif; ?>
            </tr>
        </thead>
        <?php
    }
}
