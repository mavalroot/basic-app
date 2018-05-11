<?php

namespace models;

use utilities\base\BaseModel;

/**
 *
 */
class Perifericos extends BaseModel
{
    protected $columnas = [
        'Descripción' => 'descripcion'
    ];

    protected $searchBy = [
        'Descripción' => 'descripcion'
    ];

    public static function tableName()
    {
        return 'perifericos';
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