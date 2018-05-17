<?php

namespace controllers;

use models\Usuarios;
use utilities\base\BaseController;
use utilities\helpers\html\Html;
use utilities\helpers\validation\Errors;

/**
 *
 */
class UsuariosController extends BaseController
{
    protected static $rol = 2;

    public function __construct()
    {
        $model = new Usuarios();
        $this->model = $model;
    }

    public function index($pagLimit, $pagOffset)
    {
        $this->permission();
        $searchTerm = isset($_GET['search']) ? Html::h($_GET['search']) : '';
        $searchBy = isset($_GET['by']) ? Html::h($_GET['by']) : '';
        $model = new Usuarios();
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

    public function view($id)
    {
        self::permission();
        $model = new Usuarios(['id' => $id]);
        if (!$model->readOne()) {
            Errors::notFound();
        }
        return $model;
    }

    public function create()
    {
        $this->permission();

        return $model;
    }

    public function update($id)
    {
        $this->permission();
        $model = $this->findModel($id);

        return $model;
    }
}
