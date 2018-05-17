<?php

namespace controllers;

use models\Aparatos;
use models\AparatosUsuarios;
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
        if ($model->readOne()) {
            $especifico = $model->getModelByType();
            $especifico->readOne();
        } else {
            Errors::notFound();
        }
        return [
        'aparato' => $model,
        'especifico' => $especifico
        ];
    }

    public function create()
    {
        $this->permission();
        $model = new Aparatos();
        $especifico = false;
        if ($_POST) {
            if (isset($_POST['aparatos'])) {
                $model->load($_POST['aparatos']);
            }
            if ($model->validate()) {
                $especifico = $model->getModelByType();
                if (isset($_POST[$model->tipo])) {
                    $especifico->load($_POST[$model->tipo]);
                }
                if ($especifico->validate() && $model->create()) {
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
        $especifico = $model->getDatosAsociados();

        if ($_POST) {
            $no = ['usuario_id', 'delegacion_id', 'tipo'];
            if (isset($_POST['aparatos'])) {
                foreach ($no as $value) {
                    if ($model->$value != '') {
                        unset($_POST['aparatos'][$value]);
                    }
                }
                $model->load($_POST['aparatos']);
            }
            if (isset($_POST[$model->tipo])) {
                $especifico->load($_POST[$model->tipo]);
            }

            if (($model->validate() && $especifico->validate()) && ($model->update() && $especifico->update())) {
                // Se actualiza el registro.
                Html::alert('success', 'El registro se ha actualizado');
                $model->createRecord('update');
            } else {
                // Si no se pudo actualizar, se da un aviso al usuario.
                Html::alert('danger', 'No se ha podido actualizar el registro.');
            }
        }

        return [
            'aparato' => $model,
            'especifico' => $especifico,
        ];
    }

    public function cambiarUsuario()
    {
        // $this->permission();
        if ($_POST) {
            extract($_POST);
            if (!isset($id, $usuario_id)) {
                return false;
            }

            $model = $this->findModel($id);
            $old = $model->usuario_id;
            $model->usuario_id = $usuario_id;
            if ($usuario_id != $old && $model->validate() && $model->update()) {
                if ($old) {
                    $record = new AparatosUsuarios([
                        'aparato_id' => $id,
                        'usuario_id' => $usuario_id,
                    ]);
                    if ($record->validate()) {
                        $record->create();
                    }
                }
            }
            return true;
        }
        return false;
    }
}
