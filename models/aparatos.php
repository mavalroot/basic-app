<?php

namespace models;

use utilities\base\BaseModel;

/**
 *
 */
class Aparatos extends BaseModel
{
    protected $columnas = [
        'Utilizado por el usuario' => 'usuario_id',
        'Utilizado por la delegación' => 'delegacion_id',
        'Marca' => 'marca',
        'Modelo' => 'modelo',
        'Número de serie' => 'num_serie',
        'Fecha de compra' => 'fecha_compra',
        'Proveedor' => 'proveedor',
        'Tipo de aparato' => 'tipo',
        'Observaciones' => 'observaciones',
    ];

    protected $searchBy = $this->columnas;

    public function tableName()
    {
        return 'aparatos';
    }

    public static function rules()
    {
        return [

        ];
    }
}
