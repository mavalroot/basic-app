<?php

namespace utilities\helpers\validation;

use DateTime;
use utilities\base\BaseModel;

/**
 * Clase para validar modelos basados en BaseModel.
 */
class ModelValidator
{
    /**
     * Valida el modelo que se le pasa como parámetro, utilizando las reglas de
     * éste, que deben existir en rules().
     * @param  BaseModel $model Modelo a validar
     * @return array            Array resultante con los errores generados
     * durante la validación.
     */
    public static function validate($model)
    {
        $array = static::prepareModel($model);

        $errores = [];

        foreach ($array as $key => $value) {
            if (isset($value[2])) {
                static::checkOperation($key, $value, $errores);
            } else {
                static::checkType($key, $value, $errores);
            }
        }
        return $errores;
    }

    /**
     * Prepara el modelo para que esté acorde a la validación que se llevará
     * a cabo.
     * @param  BaseModel $model
     * @return array        Array resultante de preparar el modelo para la
     * validación, su forma es:
     *
     * [
     *  'propiedad' => ['valor', 'operador', 'operando', 'message' => 'mensaje'],
     *  'propiedad' => ['valor', 'tipo', 'message' => 'mensaje'],
     * ]
     */
    protected function prepareModel($model)
    {
        $array = [];
        foreach ($model->rules() as $value) {
            $attributes = $value[0];
            foreach ($attributes as $attribute) {
                $name = $attribute;
                $value[0] = isset($model->{$name}) ? $model->{$name} : null;
                $array[$name] = $value;
            }
        }
        return $array;
    }

    /**
     * Añade los errores.
     * @param bool      $op      Operación de comprobación que debe devolver
     * verdadero o falso.
     * @param string    $key     Nombre de la propiedad del modelo a la que se
     * le está añadiendo el error.
     * @param array     $value   Array del que se extraerá el mensaje de error
     * si existiera. En caso contrario se añadirá un mensaje de error estandar.
     * @param array     $errores Array que contiene los errores.
     */
    protected function addError($op, $key, $value, &$errores)
    {
        if (!$op) {
            $errores[$key] = isset($value['message']) ? $value['message'] : 'El campo contiene errores.';
        }
    }

    /**
     * Hace una comprobación de operadores.
     * @param  string   $key     Nombre de la propiedad del modelo a la que se
     * le está haciendo la comprobación.
     * @param  array    $array   Array que contiene los operandos y el operador
     * en formato string.
     * @param  array    $errores Array que contiene los errores.
     */
    protected function checkOperation($key, $array, &$errores)
    {
        switch ($array[1]) {
            case '>':
            static::addError(($array[0] > $array[2]), $key, $array, $errores);
            break;
            case '<':
            static::addError(($array[0] < $array[2]), $key, $array, $errores);
            break;
            case '>=':
            static::addError(($array[0] >= $array[2]), $key, $array, $errores);
            break;
            case '<=':
            static::addError(($array[0] <= $array[2]), $key, $array, $errores);
            break;
            case '=':
            case '==':
            static::addError(($array[0] == $array[2]), $key, $array, $errores);
            break;
            case '===':
            static::addError(($array[0] === $array[2]), $key, $array, $errores);
            break;
            case '!=':
            static::addError(($array[0] != $array[2]), $key, $array, $errores);
            break;
            case '!==':
            static::addError(($array[0] !== $array[2]), $key, $array, $errores);
            break;
            case 'in':
            static::addError(in_array($array[0], $array[2]), $key, $array, $errores);
            break;
            case 'not in':
            static::addError(!in_array($array[0], $array[2]), $key, $array, $errores);
            break;
            case 'max':
            static::addError((strlen($array[0]) <= $array[2]), $key, $array, $errores);
            break;
            case 'min':
            static::addError((strlen($array[0]) >= $array[2]), $key, $array, $errores);
            break;
            default:
            break;
        }
    }

    protected function checkIn($checking, $in)
    {
        foreach ($in as $value) {
            if ($checking == $value) {
                return true;
            }
        }
        return false;
    }

    /**
     * Hace una comprobación de tipos.
     * @param  string   $key     Nombre de la propiedad del modelo a la que se
     * le está haciendo la comprobación.
     * @param  array    $array   Array que contiene el valor y el tipo a
     * comprobar.
     * @param  array    $errores Array que contiene los errores.
     */
    protected function checkType($key, $array, &$errores)
    {
        switch ($array[1]) {
            case 'integer':
            static::addError(is_int($array[0]), $key, $array, $errores);
            break;
            case 'numeric':
            static::addError(is_numeric($array[0]), $key, $array, $errores);
            break;
            case 'string':
            static::addError(is_string($array[0]), $key, $array, $errores);
            break;
            case 'boolean':
            static::addError(is_bool($array[0]), $key, $array, $errores);
            break;
            case 'array':
            static::addError(is_array($array[0]), $key, $array, $errores);
            break;
            case 'required':
            static::addError((!is_null($array[0]) && $array[0] != ''), $key, $array, $errores);
            break;
            case 'null':
            static::addError(is_null($array[0]), $key, $array, $errores);
            break;
            case 'float':
            static::addError(is_float($array[0]), $key, $array, $errores);
            break;
            case 'date':
            $bool = DateTime::createFromFormat('Y-m-d', $array[0]) !== false;
            static::addError($bool, $key, $array, $errores);
            break;
        }
    }
}
