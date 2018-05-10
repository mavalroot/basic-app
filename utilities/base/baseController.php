<?php

namespace utilities\base;

use utilities\helpers\validation\Errors;
use utilities\helpers\validation\Checker;

/**
 *
 */
class BaseController
{
    /**
     * Rol a comprobar
     * @var string
     */
    protected static $rol = '';

    /**
     * Modelo
     * @var null|BaseModel
     */
    protected $model = null;

    /**
     * Constructor de la clase. Inicializa el modelo.
     */
    public function __construct()
    {
        $model = new BaseModel();
        $this->model = $model;
    }

    /**
     * Borra un registro ya existente.
     * @param  int $id Identificador del registro que se va a borrar.
     */
    public function delete($id)
    {
        $model = $this->findModel($id);
        if ($model->delete()) {
            echo 'El registro ha sido eliminado.';
        } else {
            echo 'El registro no ha podido ser eliminado.';
        }
    }

    /**
     * Encuentra un modelo a través de un identificador.
     * @param  int $id Identificador para encontrar el modelo.
     * @return BaseModel
     */
    public function findModel($id)
    {
        $model = $this->model;
        $pk = $model->primaryKey();
        $model->readOne([$pk, $id]);
        return $model;
    }

    /**
     * Comprueba el rol del rol actual para ver si tiene permisos.
     */
    public function permission()
    {
        if (!Checker::checkRole(static::$rol) && !Checker::checkRole('todo')) {
            Errors::forbidden();
        }
    }
}
