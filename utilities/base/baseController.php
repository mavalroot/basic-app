<?php

namespace utilities\base;

use utilities\helpers\validation\Errors;

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
        if (isset($model)) {
            $model->createRecord('delete');
            if ($model->delete()) {
                echo 'El registro ha sido eliminado.';
            } else {
                echo 'El registro no ha podido ser eliminado.';
            }
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
        if ($model->readOne([$pk, $id])) {
            return $model;
        }
        return null;
    }
}
