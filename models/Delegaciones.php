<?php

namespace models;

use utilities\base\BaseModel;

/**
 *
 */
class Delegaciones extends BaseModel
{
    protected $columnas = [
        'Nombre de la delegación' => 'nombre',
    ];

    protected $searchBy = [
        'Nombre de la delegación' => 'nombre',
    ];

    public static function tableName()
    {
        return 'delegaciones';
    }

    public static function rules()
    {
        return [

        ];
    }

    public function getUsuarios()
    {
    }

    public function getAparatos()
    {
    }
}
