<?php

namespace models;

use utilities\base\Database;
use utilities\base\BaseModel;

use utilities\query\QueryBuilder;

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
            [['nombre'], 'required', 'message' => 'Error: este campo es obligatorio.'],
            [['delegacion_id'], 'in', array_keys(static::getDelegaciones(true)), 'message' => 'Error: Seleccione una opción válida.'],
        ];
    }

    public static function getAllUsuarios($withEmpty = false)
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

    public function getDelegacion($withEmpty = false)
    {
        $db = new Database();
        $db = $db->getConnection();
        $query = QueryBuilder::db($db)
            ->select('id, nombre')
            ->from('delegaciones')
            ->where(['usuario_id' => $this->id])
            ->get();

        $data = $query->fetchAll();

        return $data;
    }

    public function getAparatos()
    {
        $db = new Database();
        $db = $db->getConnection();
        $query = QueryBuilder::db($db)
            ->select('id, numero_serie')
            ->from('aparatos')
            ->where(['usuario_id' => $this->id])
            ->get();

        $data = $query->fetchAll();

        return $data;
    }
}
