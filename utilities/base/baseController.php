<?php

namespace utilities\base;

use models\Permisos;

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
            $permiso = Permisos::getPermisoId($check);
            if (isset($permiso) && isset($actual)) {
                return $actual == $permiso;
            }
        }
        if (is_array($check)) {
            foreach ($check as $value) {
                $permiso = Permisos::getPermisoId($value);
                if (isset($permiso) && isset($actual) && $actual == $permiso) {
                    return true;
                }
            }
        }
        return false;
    }
}
