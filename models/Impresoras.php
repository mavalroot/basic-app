<?php

namespace models;

use utilities\base\BaseModel;

/**
 *
 */
class Impresoras extends BaseModel
{
    protected $searchBy = [
        'Cartucho' => 'cartucho',
        'Color magenta' => 'magenta',
        'Color cian' => 'cian',
        'Color amarillo' => 'amarillo',
        'Color negro' => 'negro',
        'Está en red' => 'red',
    ];

    public static function tableName()
    {
        return 'impresoras';
    }

    public static function rules()
    {
        return [
            [['red'], 'in', [0,1,'0', '1'], 'message' => 'Error: elija una opción correcta.'],
        ];
    }

    public static function primaryKey()
    {
        return 'aparato_id';
    }

    protected static function labels()
    {
        return [
            'cartucho' => 'Cartucho',
            'magenta' => 'Color magenta',
            'cian' => 'Color cian',
            'amarillo' => 'Color amarillo',
            'negro' => 'Color negro',
            'red' => 'Está en red',
        ];
    }

    /**
     * Devuelve el aparato asociado.
     * @return Aparatos|null
     */
    public function getAparato()
    {
        $model = new Aparatos(['id' => $this->aparato_id]);
        if ($model->readOne()) {
            return $model;
        }
        return null;
    }
}
