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
