<?php

namespace models;

use utilities\base\BaseModel;
use utilities\base\Database;

use utilities\query\QueryBuilder;

/**
 *
 */
class Historial extends BaseModel
{
    protected $searchBy = [
        'Número de serie' => 'num_serie',
        'Tipo de aparato' => 'tipo',
        'Marca' => 'marca',
        'Modelo' => 'modelo',
    ];

    public static function tableName()
    {
        return 'aparatos';
    }

    public function rules()
    {
        $modelo = self::getModelByType();
        return [
            [['tipo'], 'in', array_keys(static::getTypes()), 'message' => 'Error: Seleccione una opción válida.'],
            [['referencia'], 'in', array_keys($modelo->getAll())],
            [['created_by'], 'in', array_keys(Roles::getAll())],
        ];
    }

    protected static function labels()
    {
        return [
            'id' => 'ID',
            'accion' => 'Acción',
            'tipo' => 'Tipo',
            'referencia' => 'Referencia',
            'created_at' => 'Fecha de creación',
            'created_by' => 'Usuario',
        ];
    }

    /**
     * Devuelve el modelo secundario que está relacionado con este modelo de
     * historial, relleno con sus datos correspondientes.
     * @return Aparatos|Usuarios|Delegaciones|null
     */
    public function getDatosAsociados()
    {
        $model = $this->getModelByType();
        if ($model->readOne()) {
            return $model;
        }
        return null;
    }

    /**
     * Devuelve un modelo vacío dependiendo del tipo del historial.
     * @return Aparatos|Usuarios|Delegaciones|null
     */
    public function getModelByType()
    {
        $id = isset($this->referencia) ? ['id' => $this->referencia] : false;

        switch ($this->tipo) {
            case 'aparatos':
                return new Aparatos($id);
            case 'usuarios':
                return new Usuarios($id);
            case 'delegaciones':
                return new Delegaciones($id);
            default:
                return null;
        }
    }

    /**
     * Devuelve los tipos posibles.
     * @return array Valores en formato clave => valor donde "clave" es el
     * valor que se guarda en tabla y "valor" el label.
     */
    public static function getTypes()
    {
        return [
            'aparatos' => 'Aparatos',
            'usuarios' => 'Usuarios',
            'delegaciones' => 'Delegaciones',
        ];
    }
}
