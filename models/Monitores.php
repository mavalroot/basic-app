<?php

namespace models;

use utilities\base\BaseModel;

/**
 *
 */
class Monitores extends BaseModel
{
    protected $searchBy = [
        'Pulgadas' => 'pulgadas'
    ];

    public static function tableName()
    {
        return 'monitores';
    }

    public function rules()
    {
        return [
            [['pulgadas'], 'max', 255, 'message' => 'Error: Excede el número máximo de caracteres (255).'],
        ];
    }

    public static function primaryKey()
    {
        return 'aparato_id';
    }

    protected static function labels()
    {
        return [
            'pulgadas' => 'Pulgadas'
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
