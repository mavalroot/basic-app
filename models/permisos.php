<?php

namespace models;

use utilities\base\Database;
use utilities\base\BaseModel;

use utilities\query\QueryBuilder;

/**
 * Esta clase representa a los roles de la tabla roles.
 * Se usará para login y logout.
 */
class Permisos extends BaseModel
{
    const ADMIN = '';

    const NORMAL = '';

    protected $sortBy = false;

    public static function tableName()
    {
        return 'permisos';
    }

    public static function primaryKey()
    {
        return 'id';
    }

    /**
     * Devuelve los roles que están relacionados con este permiso.
     * @return array
     */
    public function getRoles()
    {
        $query = QueryBuilder::db($this->conn)
            ->from('roles')
            ->where(['permiso_id', $this->id, '='])
            ->get();

        return $query->fetchAll();
    }

    /**
     * Devuelve todos los permisos en un array del siguiente formato:
     *  [
     *      'id' => 'permiso',
     *      'id' => 'permiso',
     *  ]
     * @return array                Valores en el formato ya citado arriba.
     */
    public static function getAll()
    {
        $db = new Database();
        $db = $db->getConnection();
        $query = QueryBuilder::db($db)
            ->select('id, permiso')
            ->from('permisos')
            ->get();

        $data = $query->fetchAll();

        $new = [];
        foreach ($data as $value) {
            $new[$value['permiso']] = $value['id'];
        }
        return $new;
    }

    public static function getPermisoId($string)
    {
        $all = $this->getAll();
        return $all[$string];
    }
}
