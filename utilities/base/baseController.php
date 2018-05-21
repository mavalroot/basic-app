<?php

namespace utilities\base;

use models\Permisos;
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

    /**
     * Comprueba que el permiso del rol actual sea administrador.
     * @param string|array $check Valor a comprobar. Debe ser un permiso existente
     * de la tabla permisos.
     * @return bool
     */
    public function permission($check)
    {
        $actual = $_SESSION['permiso_id'];
        if (is_string($check)) {
            if (!isset($actual) || $actual !== $check) {
                Errors::forbidden();
            }
        }
        if (is_array($check)) {
            if (!isset($actual) || !in_array($actual, $check)) {
                Errors::forbidden();
            }
        }
    }
}
