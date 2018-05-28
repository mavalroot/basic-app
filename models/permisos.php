<?php

namespace models;

use utilities\base\Database;
use utilities\base\BaseModel;

use utilities\query\QueryBuilder;

/**
 * Esta clase representa a los usuarios de la tabla usuarios.
 * Se usará para login y logout.
 *
 * {@inheritdoc}
 */
class Permisos extends BaseModel
{
    /**
     * Administrador.
     * @var string
     */
    const ADMIN = 'admin';

    /**
     * Usuario normal.
     * @var string
     */
    const NORMAL = 'normal';

    /**
     * Permiso de lectura.
     * @var string
     */
    const LECTOR = 'lector';

    /**
     * Permiso de editor.
     * @var string
     */
    const EDITOR = 'editor';

    /**
     * Sólo puede ver los QR.
     * @var string
     */
    const QRONLY = 'qronly';


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
     * Devuelve los usuarios que están relacionados con este permiso.
     * @return array
     */
    public function getUsers()
    {
        $query = QueryBuilder::db($this->conn)
            ->from('usuarios')
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

    /**
     * Devuelve el id del permiso pasado por parámetro.
     * @param  string $string   Nombre del permiso en formato string.
     * @return int              Id del permiso.
     */
    public static function getPermisoId($string)
    {
        $all = static::getAll();
        return isset($all[$string]) ? $all[$string] : null;
    }

    /**
     * Devuelve el nombre del permiso pasado por parámetro.
     * @param  int      $id Id del permiso.
     * @return string       Nombre del permiso en formato string.
     */
    public static function getPermisoNombre($id)
    {
        $all = array_flip(static::getAll());
        return isset($all[$id]) ? $all[$id] : null;
    }
}
