<?php

namespace controllers;

use models\Permisos;
use models\Usuarios;
use utilities\base\BaseController;
use utilities\helpers\html\Html;
use utilities\helpers\validation\Errors;
use utilities\helpers\validation\Checker;

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

    /**
     * Devuelve las variables necesarias para la creación del index del modelo.
     * @param  int      $pagLimit   LIMIT del paginador.
     * @param  int      $pagOffset  OFFSET del paginador.
     * @return array                Valores en formato clave => valor listo
     * para un extract en el index.
     */
    public function index($pagLimit, $pagOffset)
    {
        Checker::permission([Permisos::LECTOR, Permisos::EDITOR, Permisos::ADMIN, Permisos::NORMAL]);
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

    /**
     * Devuelve el modelo necesario para la creación del view del modelo.
     * @param  int          $id Id del modelo a visualizar.
     * @return Usuarios         Modelo de la clase.
     */
    public function view($id)
    {
        Checker::permission([Permisos::LECTOR, Permisos::EDITOR, Permisos::ADMIN, Permisos::NORMAL]);
        $model = new Usuarios(['id' => $id]);
        if (!$model->readOne()) {
            Errors::notFound();
        }
        return $model;
    }

    /**
     * Crea una nueva fila de la tabla "usuarios" con los datos obtenidos
     * del formulario.
     * @return Usuarios Modelo vacío para usar con el formulario.
     */
    public function create()
    {
        Checker::permission([Permisos::EDITOR, Permisos::ADMIN, Permisos::NORMAL]);
        $model = new Usuarios();
        if ($_POST) {
            if (isset($_POST['usuarios'])) {
                $model->load($_POST['usuarios']);
            }
            if ($model->validate() && $model->create()) {
                Html::alert('success', 'Se ha creado el registro. Para verlo haga click <a href="view.php?id=' . $model->id . '" class="alert-link">aquí</a>.', true);
                $model->createRecord('insert');
                $model->reset();
            } else {
                Html::alert('danger', 'El registro no ha podido crearse');
            }
        }
        return $model;
    }

    /**
     * Actualiza una fila de la tabla "delegaciones" con los datos obtenidos a
     * través del formulario.
     * @param  int      $id Id de la fila a modificar.
     * @return Usuarios     Modelo de la clase cargado con sus datos
     * correspondientes, listo para un extract y la visualización en el
     * formulario.
     */
    public function update($id)
    {
        Checker::permission([Permisos::EDITOR, Permisos::ADMIN, Permisos::NORMAL]);
        $model = $this->findModel($id);
        if (!$model->readOne()) {
            Errors::notFound();
        }

        if ($_POST) {
            if (isset($_POST['usuarios'])) {
                $model->load($_POST['usuarios']);
            }
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

    /**
     * Borra un registro ya existente.
     * @param  int $id Identificador del registro que se va a borrar.
     */
    public function delete($id)
    {
        if (!Checker::checkPermission(Permisos::ADMIN, Permisos::NORMAL)) {
            echo '<i class="fas fa-exclamation-circle"></i> No tienes permiso de borrado.';
            return;
        }
        $model = $this->findModel($id);
        if (isset($model)) {
            $model->createRecord('delete');
            if ($model->delete()) {
                echo 'El registro ha sido eliminado.';
            } else {
                echo 'El registro no ha podido ser eliminado.';
            }
        }
    }
}
