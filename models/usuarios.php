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
            [['delegacion_id'], 'in', array_keys(Delegaciones::getAllDelegaciones(true)), 'message' => 'Error: Seleccione una opción válida.'],
        ];
    }

    protected static function labels()
    {
        return [
            'nombre' => 'Nombre completo',
            'delegacion_id' => 'Delegación',
            'extension' => 'Extensión de teléfono',
        ];
    }

    public function getDelegacion()
    {
        if ($this->delegacion_id) {
            $usuario = new Delegaciones([
                'id' => $this->delegacion_id
            ]);
            if ($usuario->readOne()) {
                return $usuario;
            }
        }
        return null;
    }

    public function getNombreDelegacion()
    {
        $delegacion = $this->getDelegacion();
        return isset($delegacion->nombre) ? $delegacion->nombre : '';
    }

    public function getAparatosActuales()
    {
        $query = QueryBuilder::db($this->conn)
            ->select('*')
            ->from('aparatos')
            ->where(['usuario_id' => $this->id])
            ->get();

        $data = $query->fetchAll();

        return $data;
    }

    public function getAparatosAnteriores()
    {
        $query = QueryBuilder::db($this->conn)
        ->select('a.*')
        ->from('aparatos a')
        ->join('aparatos_usuarios b', ['a.id', 'b.aparato_id'])
        ->where(['a.usuario_id', $this->id])
        ->get();

        $data = $query->fetchAll();

        return $data;
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
}
