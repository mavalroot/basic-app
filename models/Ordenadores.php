<?php

namespace models;

use utilities\base\BaseModel;

/**
 *
 */
class Ordenadores extends BaseModel
{
    protected $searchBy = [
        'Microprocesador' => 'micro',
        'Memoria RAM' => 'memoria',
        'Disco duro' => 'disco_duro',
        'Tipo de Disco duro' => 'tipo_disco',
        'IP' => 'ip',
        'Sistema operativo' => 'sist_op'
    ];

    public static function tableName()
    {
        return 'ordenadores';
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

    protected static function labels()
    {
        return [
            'micro' => 'Microprocesador',
            'memoria' => 'Memoria RAM',
            'disco_duro' => 'Disco duro',
            'tipo_disco' => 'Tipo de Disco duro',
            'ip' => 'IP',
            'sist_op' => 'Sistema operativo'
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
