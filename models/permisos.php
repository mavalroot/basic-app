<?php

namespace models;

use utilities\base\BaseModel;

use utilities\query\QueryBuilder;

/**
 * Esta clase representa a los roles de la tabla roles.
 * Se usará para login y logout.
 */
class Permisos extends BaseModel
{
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
}
