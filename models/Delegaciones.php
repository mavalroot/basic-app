<?php

namespace models;

use utilities\base\Database;
use utilities\base\BaseModel;

use utilities\query\QueryBuilder;

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
            [['nombre'], 'required', 'message' => 'Error: este campo es obligatorio.']
        ];
    }

    public static function getAllDelegaciones($withEmpty = false)
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

    public function getUsuarios()
    {
        $query = QueryBuilder::db($this->conn)
            ->select('id, nombre')
            ->from('usuarios')
            ->where(['delegacion_id' => $this->id])
            ->get();

        $data = $query->fetchAll();

        return $data;
    }

    public function getAparatos()
    {
        $query = QueryBuilder::db($this->conn)
            ->select('id, numero_serie')
            ->from('aparatos')
            ->where(['delegacion_id' => $this->id])
            ->get();

        $data = $query->fetchAll();

        return $data;
    }
}
