<?php

namespace controllers;

use models\Usuarios;
use models\Permisos;
use utilities\base\BaseController;
use utilities\helpers\html\Html;
use utilities\helpers\validation\Errors;
use utilities\helpers\validation\Checker;

/**
 *
 */
class UsuariosController extends BaseController
{
    protected static $usuario = 2;

    public function __construct()
    {
        $model = new Usuarios();
        $this->model = $model;
    }

    /**
     * Acción de login.
     *
     * Primero comprueba que el usuario no esté ya loggeado. En caso de que lo
     * esté redigirá al index, en caso de que no se comprobará si se han
     * recibido los parámetros "Nombre" y "Password" por POST, para acto
     * seguido hacer las debidas comprobaciones.
     *
     * Se lanzará un mensaje de error si el usuario o la contraseña
     * introducidos no son válidos, o se redigirá al index si el login ha
     * tenido éxito.
     */
    public function login()
    {
        $usuario = new Usuarios();
        if ($usuario->isLogged()) {
            header("Location: ../site/index.php");
            exit;
        }
        if ($_POST) {
            $usuario->nombre = $_POST['nombre'];
            $usuario->password_hash = $_POST['password_hash'];

            if ($usuario->login()) {
                if (isset($_SESSION['previousUrl'])) {
                    header("Location: " . $_SESSION['previousUrl']);
                    unset($_SESSION['previousUrl']);
                    exit;
                }
                header("Location: ../site/index.php");
                exit;
            } else {
                Html::alert('danger', 'El usuario o la contraseña introducidos no son válidos.');
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
        Checker::permission(Permisos::ADMIN);
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
     * @param  int      $id Id del modelo a visualizar.
     * @return Usuarios        Modelo de la clase.
     */
    public function view($id)
    {
        Checker::permission(Permisos::ADMIN);
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
        Checker::permission(Permisos::ADMIN);
        $model = new Usuarios();
        if ($_POST) {
            if (isset($_POST[$model->tableName()])) {
                $model->load($_POST[$model->tableName()]);
            }
            if ($model->validate() && $model->create()) {
                Html::alert('success', 'Se ha creado el usuario. Para verlo haga click <a href="view.php?id=' . $model->id . '" class="alert-link">aquí</a>.', true);
                $model->createRecord('insert');
                $model->reset();
            } else {
                Html::alert('danger', 'El usuario no ha podido crearse');
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
        Checker::permission(Permisos::ADMIN);
        $model = $this->findModel($id);
        if (!$model->readOne()) {
            Errors::notFound();
        }
        $old = $model->password_hash;

        if ($_POST) {
            if (isset($_POST[$model->tableName()])) {
                $model->load($_POST[$model->tableName()]);
            }
            if (isset($_POST[$model->tableName()]['password_hash']) && $_POST[$model->tableName()]['password_hash'] == '') {
                $model->password_hash = $old;
            } else {
                $model->password_hash = password_hash($model->password_hash, PASSWORD_DEFAULT);
            }
            if ($model->validate() && $model->update()) {
                // Se actualiza el registro.
                Html::alert('success', 'El usuario se ha actualizado');
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
        if (!Checker::checkPermission(Permisos::ADMIN)) {
            echo '<i class="fas fa-exclamation-circle"></i> No tienes permiso de borrado.';
            return;
        }
        $model = $this->findModel($id);
        if (isset($model)) {
            $old = $model;
            if ($model->delete()) {
                $old->createRecord('delete');
                unset($old);
                echo '<i class="fas fa-check"></i> El usuario ha sido eliminado.';
            } else {
                echo '<i class="fas fa-exclamation-circle"></i> El usuario no ha podido ser eliminado.';
            }
        }
    }
}
