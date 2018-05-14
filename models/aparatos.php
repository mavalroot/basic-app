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
            [['tipo'], 'in', array_keys(static::getTypes()), 'message' => 'El tipo no es correcto. Seleccione una opción válida.']
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
        $id = isset($this->id) ? ['aparato_id' => $this->id] : false;

        switch ($this->tipo) {
            case 'ordenador':
                return new Ordenadores($id);
            case 'periferico':
                return new Perifericos($id);
            case 'impresora':
                return new Impresoras($id);
            case 'electronica_red':
                return new ElectronicaRed($id);
            case 'monitor':
                return new Monitores($id);
            default:
                return null;
        }
    }

    public static function getTypes()
    {
        return [
            'ordenadores' => 'Ordenadores',
            'impresoras' => 'Impresoras',
            'monitores' => 'Monitores',
            'perifericos' => 'Periféricos',
            'electronica_red' => 'Electrónica de red',
        ];
    }
}
