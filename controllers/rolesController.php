<?php

namespace controllers;

use models\Roles;

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
        $this->permission();
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
        self::permission();
        $model = new Roles(['id' => $id]);
        if (!$model->readOne()) {
            Errors::notFound();
        }
        return $model;
    }
}
