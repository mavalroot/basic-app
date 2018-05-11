<?php

namespace models;

use utilities\base\BaseModel;

/**
 *
 */
class Monitores extends BaseModel
{
    protected $columnas = [
        'Pulgadas' => 'pulgadas'
    ];

    protected $searchBy = [
        'Pulgadas' => 'pulgadas'
    ];

    public static function tableName()
    {
        return 'monitores';
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