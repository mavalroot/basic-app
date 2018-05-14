<?php

namespace models;

use utilities\base\BaseModel;

/**
 *

 */
class Usuarios extends BaseModel
{
    protected $columnas = [
        'Nombre completo' => 'nombre',
        'Delegación' => 'delegacion_id',
        'Extensión de teléfono' => 'extension',
    ];

    protected $searchBy = [
        'Nombre completo' => 'nombre',
        'Extensión de teléfono' => 'extension',
    ];

    public static function tableName()
    {
        return 'usuarios';
    }

    public static function rules()
    {
        return [

        ];
    }

    public function getDelegacion()
    {
    }

    public function getAparatos()
    {
    }
}
