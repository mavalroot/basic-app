<?php

namespace controllers;

use models\Aparatos;
use models\Permisos;
use models\AparatosUsuarios;
use utilities\base\BaseController;
use utilities\helpers\validation\Checker;
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

    /**
     * Devuelve las variables necesarias para la creación del view del modelo.
     * @param  int      $id Id del modelo a visualizar.
     * @return array        Valores en formato clave => valor listos para un
     * extract en view.
     */
    public function view($id)
    {
        Checker::permission([Permisos::LECTOR, Permisos::EDITOR, Permisos::ADMIN, Permisos::NORMAL]);
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

    /**
     * Crea una nueva fila de la tabla "aparatos"  con los datos obtenidos a
     * través del formulario, y también una de las tablas relacionadas con ésta
     * (monitores, ordenadores, perifericos, impresoras, electronica_red).
     * @return array Valores en formato clave => valor listos para un extract.
     * En caso de que no se cree nada se devuelven los modelos vacíos para usar
     * en los fomularios.
     */
    public function create()
    {
        Checker::permission([Permisos::EDITOR, Permisos::ADMIN, Permisos::NORMAL]);
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

    /**
     * Actualiza una fila de la tabla "aparatos" con los datos obtenidos a
     * través del formulario, y también una de las tablas relacionadas con ésta
     * (monitores, ordenadores, perifericos, impresoras, electronica_red).
     * @param  int      $id Id de la fila a modificar.
     * @return array        Valores en formato clave => valor ya cargados con
     * los datos correspondientes para mostrar en el formulario. Listo para
     * hacer un extract.
     */
    public function update($id)
    {
        Checker::permission([Permisos::EDITOR, Permisos::ADMIN, Permisos::NORMAL]);
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

    /**
     * Cambia el usuario que está utilizando actualmente un aparato, y
     * posteriormente lo guarda en el historial "aparatos_usuarios".
     * @return bool
     */
    public function cambiarUsuario()
    {
        Checker::permission([Permisos::EDITOR, Permisos::ADMIN, Permisos::NORMAL]);
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
                        'usuario_id' => $old,
                    ]);
                    if ($record->validate()) {
                        $record->create();
                        $model->createRecord('cambiar');
                    }
                }
            }
            return true;
        }
        return false;
    }

    /**
     * Borra un registro ya existente.
     * @param  int $id Identificador del registro que se va a borrar.
     */
    public function delete($id)
    {
        if (!Checker::checkPermission([Permisos::ADMIN, Permisos::NORMAL])) {
            echo '<i class="fas fa-exclamation-circle"></i> No tienes permiso de borrado.';
            return;
        }
        $model = $this->findModel($id);
        if (isset($model)) {
            $model->createRecord('delete');
            if ($model->delete()) {
                echo '<i class="fas fa-check"></i> El aparato ha sido eliminado.';
            } else {
                echo '<i class="fas fa-exclamation-circle"></i> El aparato no ha podido ser eliminado.';
            }
        }
    }
}
