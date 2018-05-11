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

    protected $searchBy = [
        'Usuario' => 'usuario_id',
        'Delegación' => 'delegacion_id',
        'Marca' => 'marca',
        'Modelo' => 'modelo',
        'Número de serie' => 'num_serie',
        'Fecha de compra' => 'fecha_compra',
        'Proveedor' => 'proveedor',
        'Tipo de aparato' => 'tipo',
        'Observaciones' => 'observaciones',
    ];

    public static function tableName()
    {
        return 'aparatos';
    }

    public static function rules()
    {
        return [

        ];
    }

    public function getDatosAsociados()
    {
        $model = $this->getModelByType();
        if ($model->readOne()) {
            return $model;
        }
        return null;
    }

    public function getModelByType()
    {
        switch ($this->tipo) {
            case 'ordenador':
                return new Ordenadores(['aparato_id' => $this->id]);
            case 'periferico':
                return new Perifericos(['aparato_id' => $this->id]);
            case 'impresora':
                return new Impresoras(['aparato_id' => $this->id]);
            case 'electronica':
                return new ElectronicaRed(['aparato_id' => $this->id]);
            case 'monitor':
                return new Monitores(['aparato_id' => $this->id]);
            default:
                return null;
        }
    }
}
