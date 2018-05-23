<?php

namespace models;

use utilities\base\BaseModel;

/**
 *
 */
class ElectronicaRed extends BaseModel
{
    protected $searchBy = [
        'Descripción' => 'descripcion'
    ];

    public static function tableName()
    {
        return 'electronica_red';
    }

    public function rules()
    {
        return [
            [['ubicacion', 'tipo'], 'max', 255, 'message' => 'Error: Excede el número máximo de caracteres (255).'],
        ];
    }

    public static function primaryKey()
    {
        return 'aparato_id';
    }

    protected static function labels()
    {
        return [
            'ubicacion' => 'Ubicación',
            'tipo' => 'Tipo',
            'descripcion' => 'Descripción'
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
