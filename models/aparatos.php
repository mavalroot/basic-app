<?php

namespace models;

use utilities\base\BaseModel;
use utilities\base\Database;

use utilities\query\QueryBuilder;

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
            [['usuario_id'], 'in', array_keys(static::getUsuarios(true)), 'message' => 'Error: Seleccione una opción válida.'],
            [['delegacion_id'], 'in', array_keys(static::getDelegaciones(true)), 'message' => 'Error: Seleccione una opción válida.'],
            [['tipo'], 'in', array_keys(static::getTypes()), 'message' => 'Error: Seleccione una opción válida.']
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
            case 'ordenadores':
                return new Ordenadores($id);
            case 'perifericos':
                return new Perifericos($id);
            case 'impresoras':
                return new Impresoras($id);
            case 'electronica_red':
                return new ElectronicaRed($id);
            case 'monitores':
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

    public static function getUsuarios($withEmpty = false)
    {
        $db = new Database();
        $db = $db->getConnection();
        $query = QueryBuilder::db($db)
            ->select('id, nombre')
            ->from('usuarios')
            ->get();

        $data = $query->fetchAll();

        $new = [];
        if ($withEmpty) {
            $new[''] = 'Ningún';
        }
        foreach ($data as $value) {
            $new[$value['id']] = $value['nombre'];
        }

        return $new;
    }

    public static function getDelegaciones($withEmpty = false)
    {
        $db = new Database();
        $db = $db->getConnection();
        $query = QueryBuilder::db($db)
            ->select('id, nombre')
            ->from('delegaciones')
            ->get();

        $data = $query->fetchAll();

        $new = [];
        if ($withEmpty) {
            $new[''] = 'Ningún';
        }
        foreach ($data as $value) {
            $new[$value['id']] = $value['nombre'];
        }

        return $new;
    }
}
