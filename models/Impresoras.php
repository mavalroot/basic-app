<?php

namespace models;

use utilities\base\BaseModel;

/**
 *
 */
class Impresoras extends BaseModel
{
    protected $columnas = [
        'Cartucho' => 'cartucho',
        'Color magenta' => 'magenta',
        'Color cian' => 'cian',
        'Color amarillo' => 'amarillo',
        'Está en red' => 'red',
    ];

    protected $searchBy = [
        'Cartucho' => 'cartucho',
        'Color magenta' => 'magenta',
        'Color cian' => 'cian',
        'Color amarillo' => 'amarillo',
        'Está en red' => 'red',
    ];

    public static function tableName()
    {
        return 'impresoras';
    }

    public static function rules()
    {
        return [

        ];
    }

    public static function primaryKey()
    {
        return 'aparato_id';
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
