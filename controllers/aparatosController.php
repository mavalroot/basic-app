<?php

namespace controllers;

use models\Aparatos;
use utilities\base\BaseController;
use utilities\helpers\html\Html;
use utilities\helpers\validation\Errors;

/**
 *
 */
class AparatosController extends BaseController
{
    protected static $rol = 2;

    public function __construct()
    {
        $model = new Aparatos();
        $this->model = $model;
    }

    public function index($pagLimit, $pagOffset)
    {
        $this->permission();
        $searchTerm = isset($_GET['search']) ? Html::h($_GET['search']) : '';
        $searchBy = isset($_GET['by']) ? Html::h($_GET['by']) : '';
        $model = new Aparatos();
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
        $model = new Aparatos(['id' => $id]);
        if (!$model->readOne()) {
            Errors::notFound();
        }
        return $model;
    }

    public function create()
    {
        $this->permission();
        $model = new Aparatos();
        $especifico = false;
        if (isset($_POST['aparatos'])) {
            $model->load($_POST['aparatos']);
            if ($model->validate()) {
                $especifico = $model->getModelByType();
                $especifico->load($_POST[$model->tipo]);
                if ($especifico->validate()) {
                    if ($model->create()) {
                        $especifico->aparato_id = $model->id;
                        $especifico->create();
                        Html::alert('success', 'Se ha creado el registro. Para verlo haga click <a href="view.php?id=' . $model->id . '" class="alert-link">aquí</a>.', true);
                        $model->createRecord('insert');
                        $model->reset();
                        $especifico->reset();
                    } else {
                        Html::alert('danger', 'El registro no ha podido crearse');
                    }
                }
            }
        }

        return [
            'aparato' => $model,
            'especifico' => $especifico,
        ];
    }

    public function update($id)
    {
        $this->permission();
        $model = $this->findModel($id);
        if (!$model->readOne()) {
            Errors::notFound();
        }

        if (isset($_POST['aparatos'])) {
            $model->load($_POST['aparatos']);

            if ($model->validate() && $model->update()) {
                // Se actualiza el registro.
                Html::alert('success', 'El registro se ha actualizado');
                $model->createRecord('update');
            } else {
                // Si no se pudo actualizar, se da un aviso al usuario.
                Html::alert('danger', 'No se ha podido actualizar el registro.');
            }
        }

        return $model;
    }
}
