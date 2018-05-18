<?php

namespace controllers;

use models\Historial;
use utilities\base\BaseController;
use utilities\helpers\html\Html;
use utilities\helpers\validation\Errors;

/**
 *
 */
class HistorialController extends BaseController
{
    protected static $rol = 2;

    public function __construct()
    {
        $model = new Historial();
        $this->model = $model;
    }

    /**
     * Devuelve las variables necesarias para la creación del index del modelo.
     * @param  int      $pagLimit   LIMIT del paginador.
     * @param  int      $pagOffset  OFFSET del paginador.
     * @return array                Valores en formato clave => valor listo
     * para un extract en el index.
     */
    public function index($pagLimit, $pagOffset)
    {
        $this->permission();
        $searchTerm = isset($_GET['search']) ? Html::h($_GET['search']) : '';
        $searchBy = isset($_GET['by']) ? Html::h($_GET['by']) : '';
        $model = new Historial();
        $query = $model->readAll([
            'searchTerm' => $searchTerm,
            'searchBy' => $searchBy,
            'limit' => $pagLimit,
            'offset' => $pagOffset,
        ]);

        if ($searchTerm) {
            $url = "index.php?search={$searchTerm}&by={$searchBy}&";
            $rows = $model->countAll([
                'searchTerm' => $searchTerm,
                'searchBy' => $searchBy
            ]);
        } else {
            $url = "index.php?";
            $rows = $model->countAll();
        }

        return [
            'rows' => $rows,
            'url' => $url,
            'query' => $query,
            'model' => $model,
            'searchTerm' => $searchTerm
        ];
    }

    /**
     * Devuelve el modelo necesario para la creación del view del modelo.
     * @param  int          $id Id del modelo a visualizar.
     * @return Historial        Modelo de la clase.
     */
    public function view($id)
    {
        self::permission();
        $model = new Historial(['id' => $id]);
        if (!$model->readOne()) {
            Errors::notFound();
        }
        return $model;
    }
}
