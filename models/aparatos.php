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
        'Número de serie' => 'num_serie',
        'Tipo de aparato' => 'tipo',
        'Marca' => 'marca',
        'Modelo' => 'modelo',
    ];

    public static function tableName()
    {
        return 'aparatos';
    }

    public static function rules()
    {
        return [
            [['usuario_id'], 'in', array_keys(Usuarios::getAllUsuarios(true)), 'message' => 'Error: Seleccione una opción válida.'],
            [['delegacion_id'], 'in', array_keys(Delegaciones::getAllDelegaciones(true)), 'message' => 'Error: Seleccione una opción válida.'],
            [['tipo'], 'in', array_keys(static::getTypes()), 'message' => 'Error: Seleccione una opción válida.']
        ];
    }

    protected static function labels()
    {
        return [
            'usuario_id' => 'Utilizado por el usuario',
            'delegacion_id' => 'Utilizado por la delegación',
            'marca' => 'Marca',
            'modelo' => 'Modelo',
            'num_serie' => 'Número de serie',
            'fecha_compra' => 'Fecha de compra',
            'proveedor' => 'Proveedor',
            'tipo' => 'Tipo de aparato',
            'observaciones' => 'Observaciones',
        ];
    }

    public function getDatosAsociados()
    {
        $model = $this->getModelByType();
        $model->aparato_id = $this->id;
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

    public static function getAllAparatos($withEmpty = false)
    {
        $db = new Database();
        $db = $db->getConnection();
        $query = QueryBuilder::db($db)
            ->select('id, num_serie')
            ->from('aparatos')
            ->get();

        $data = $query->fetchAll();

        $new = [];
        if ($withEmpty) {
            $new[''] = 'Ningún';
        }
        foreach ($data as $value) {
            $new[$value['id']] = $value['num_serie'];
        }

        return $new;
    }

    public function getUsuario()
    {
        if ($this->usuario_id) {
            $usuario = new Usuarios([
                'id' => $this->usuario_id
            ]);
            if ($usuario->readOne()) {
                return $usuario;
            }
        }
        return null;
    }

    public function getNombreUsuario()
    {
        $user = $this->getUsuario();
        return isset($user->nombre) ? $user->nombre : '';
    }

    public function getNombreDelegacion()
    {
        $delegacion = $this->getDelegacion();
        return isset($delegacion->nombre) ? $delegacion->nombre : '';
    }

    public function getDelegacion($withEmpty = false)
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

    public function getUsuariosAnteriores()
    {
        $query = QueryBuilder::db($this->conn)
        ->select('au.*, u.nombre')
        ->from('aparatos_usuarios au')
        ->join('usuarios u', ['au.usuario_id', 'u.id'])
        ->where(['aparato_id', $this->id])
        ->orderBy(['created_at DESC'])
        ->get();

        return $query->fetchAll();
    }

    public function getTipoSingular()
    {
        switch ($this->tipo) {
            case 'ordenadores':
                return 'Ordenador';
            case 'perifericos':
                return 'Periférico';
            case 'impresoras':
                return 'Impresora';
            case 'electronica_red':
                return 'Electrónica de Red';
            case 'monitores':
                return 'Monitor';
            default:
                return '';
        }
    }

    public function getQRData($exclude = [])
    {
        $data = 'Usuario actual: '
        . $this->getNombreUsuario() . '.\nDelegación: '
        . $this->getNombreDelegacion() . '.\n';

        $data .= parent::getQRData(['id', 'usuario_id', 'delegacion_id']);
        return $data;
        // $data = '';
        // $columnas = $this->getAllColumns();
        // foreach ($columnas as $columna) {
        //     if (in_array($columna, $exclude)) {
        //         continue;
        //     }
        //     $data .= ($this->getLabel($columna) ?: $columna) . ': ' . (isset($this->$columna) ? $this->$columna : '') . '.\n';
        // }
        // return $data;
    }
}
