<?php

namespace controllers;

use models\Ejemplo;
use models\Permisos;
use utilities\base\BaseController;
use utilities\helpers\html\Html;
use utilities\helpers\validation\Errors;
use utilities\helpers\validation\Checker;

/**
 *
 */
class EjemploController extends BaseController
{
    public function __construct()
    {
        $model = new Ejemplo();
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
        // Checker::permission();
        $searchTerm = isset($_GET['search']) ? Html::h($_GET['search']) : '';
        $searchBy = isset($_GET['by']) ? Html::h($_GET['by']) : '';
        $model = new Ejemplo();
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
     * @return Ejemplo          Modelo de la clase.
     */
    public function view($id)
    {
        // Checker::permission();
        $model = new Ejemplo(['id' => $id]);
        if (!$model->readOne()) {
            Errors::notFound();
        }
        return $model;
    }

    /**
     * Crea una nueva fila de la tabla con los datos obtenidos a través del
     * formulario.
     * @return Ejemplo Modelo vacío para usar con el formulario.
     */
    public function create()
    {
        // Checker::permission();
        $model = new Ejemplo();
        if ($_POST) {
            if (isset($_POST[$model->tableName()])) {
                $model->load($_POST[$model->tableName()]);
            }
            if ($model->validate() && $model->create()) {
                Html::alert('success', 'Se ha creado el ejemplo. Para verlo haga click <a href="view.php?id=' . $model->id . '" class="alert-link">aquí</a>.', true);
                $model->reset();
            } else {
                Html::alert('danger', 'El ejemplo no ha podido crearse');
            }
        }
        return $model;
    }

    /**
     * Actualiza una fila de la tabla con los datos obtenidos a través del
     * formulario.
     * @param  int      $id Id de la fila a modificar.
     * @return Ejemplo      Modelo de la clase cargado con sus datos
     * correspondientes, listo para un extract y la visualización en el
     * formulario.
     */
    public function update($id)
    {
        Checker::permission([Permisos::ADMIN, Permisos::NORMAL, Permisos::EDITOR]);
        $model = $this->findModel($id);
        if (!$model->readOne()) {
            Errors::notFound();
        }

        if ($_POST) {
            if (isset($_POST[$model->tableName()])) {
                $model->load($_POST[$model->tableName()]);
            }
            if ($model->validate() && $model->update()) {
                // Se actualiza el registro.
                Html::alert('success', 'El ejemplo se ha actualizado');
            } else {
                // Si no se pudo actualizar, se da un aviso al usuario.
                Html::alert('danger', 'No se ha podido actualizar el ejemplo.');
            }
        }
        return $model;
    }

    /**
     * Borra un ejemplo ya existente.
     * @param  int $id Identificador del ejemplo que se va a borrar.
     */
    public function delete($id)
    {
        if (!Checker::checkPermission([Permisos::ADMIN, Permisos::NORMAL])) {
            echo '<i class="fas fa-exclamation-circle"></i> No tienes permiso de borrado.';
            return;
        }
        $model = $this->findModel($id);
        if (isset($model)) {
            if ($model->delete()) {
                echo '<i class="fas fa-check"></i> El ejemplo ha sido eliminado.';
            } else {
                echo '<i class="fas fa-exclamation-circle"></i> El ejemplo no ha podido ser eliminado.';
            }
        }
    }
}
