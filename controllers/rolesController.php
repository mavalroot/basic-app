<?php

namespace controllers;

use models\Roles;
use models\Permisos;

use utilities\base\BaseController;
use utilities\helpers\html\Html;

use utilities\helpers\validation\Errors;

/**
 *
 */
class RolesController extends BaseController
{
    protected static $rol = 2;

    public function __construct()
    {
        $model = new Roles();
        $this->model = $model;
    }

    /**
     * Acción de login.
     *
     * Primero comprueba que el rol no esté ya loggeado. En caso de que lo
     * esté redigirá al index, en caso de que no se comprobará si se han
     * recibido los parámetros "Nombre" y "Password" por POST, para acto
     * seguido hacer las debidas comprobaciones.
     *
     * Se lanzará un mensaje de error si el rol o la contraseña
     * introducidos no son válidos, o se redigirá al index si el login ha
     * tenido éxito.
     */
    public function login()
    {
        $rol = new Roles();
        if ($rol->isLogged()) {
            header("Location: ../site/index.php");
            exit;
        }
        if ($_POST) {
            $rol->nombre = $_POST['nombre'];
            $rol->password_hash = $_POST['password_hash'];

            if ($rol->login()) {
                header("Location: ../site/index.php");
                exit;
            } else {
                Html::alert('danger', 'El rol o la contraseña introducidos no son válidos.');
            }
        }
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
        $this->permission(Permisos::getPermisoId(Permisos::ADMIN));
        $searchTerm = isset($_GET['search']) ? Html::h($_GET['search']) : '';
        $searchBy = isset($_GET['by']) ? Html::h($_GET['by']) : '';
        $model = new Roles();
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
     * @param  int      $id Id del modelo a visualizar.
     * @return Roles        Modelo de la clase.
     */
    public function view($id)
    {
        $this->permission(Permisos::getPermisoId(Permisos::ADMIN));
        $model = new Roles(['id' => $id]);
        if (!$model->readOne()) {
            Errors::notFound();
        }
        return $model;
    }

    /**
     * Crea una nueva fila de la tabla "usuarios" con los datos obtenidos
     * del formulario.
     * @return Roles Modelo vacío para usar con el formulario.
     */
    public function create()
    {
        $this->permission(Permisos::getPermisoId(Permisos::ADMIN));
        $model = new Roles();
        if ($_POST) {
            if (isset($_POST['roles'])) {
                $model->load($_POST['roles']);
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
     * @return Roles     Modelo de la clase cargado con sus datos
     * correspondientes, listo para un extract y la visualización en el
     * formulario.
     */
    public function update($id)
    {
        $this->permission(Permisos::getPermisoId(Permisos::ADMIN));
        $model = $this->findModel($id);
        if (!$model->readOne()) {
            Errors::notFound();
        }
        $old = $model->password_hash;

        if ($_POST) {
            if (isset($_POST['roles'])) {
                $model->load($_POST['roles']);
            }
            if (isset($_POST['roles']['password_hash']) && $_POST['roles']['password_hash'] == '') {
                $model->password_hash = $old;
            } else {
                $model->password_hash = password_hash($model->password_hash, PASSWORD_DEFAULT);
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
}
