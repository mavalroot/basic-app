<?php

namespace models;

use PDO;

use utilities\base\Database;
use utilities\base\BaseModel;
use utilities\query\QueryBuilder;

/**
 * Esta clase representa a los roles de la tabla roles.
 * Se usará para login y logout.
 */
class Roles extends BaseModel
{

    /**
     * Nombre de rol, que se corresponde al nombre de rol de la tabla.
     * @var string
     */
    public $nombre;
    /**
     * Contraseña, que se corresponde con la contraseña de la tabla.
     * @var string
     */
    public $password_hash;

    protected $sortBy = 'last_con DESC';

    protected $searchBy = ['Nombre de usuario' => 'nombre'];

    public static function tableName()
    {
        return 'roles';
    }

    public static function primaryKey()
    {
        return 'id';
    }

    protected static function labels()
    {
        return [
            'nombre' => 'Nombre de usuario',
            'last_con' => 'Última conexión',
            'permiso' => 'Permisos',
        ];
    }

    /**
     * Efectúa el loggin.
     */
    public function login()
    {
        $query = QueryBuilder::db($this->conn)
        ->select('*')
        ->from(self::tableName())
        ->where(['nombre', $this->nombre])
        ->get();

        $row = $query->fetch(PDO::FETCH_ASSOC);
        $password_hash = $row['password_hash'];

        if (password_verify($this->password_hash, $password_hash)) {
            $_SESSION['id'] = $row['id'];
            $_SESSION['nombre'] = $this->nombre;
            $_SESSION['permiso_id'] = $row['permiso_id'];
            $this->readOne(['nombre', $this->nombre]);
            $this->last_con = date("Y-m-d H:i:s");
            $this->update();
            return true;
        }
        return false;
    }

    /**
     * Comprueba si el rol actual está loggeado.
     * @return bool
     */
    public function isLogged()
    {
        if (isset($_SESSION['nombre'])) {
            return $this->readOne(['nombre', $_SESSION['nombre']]);
        }
        return false;
    }

    /**
     * Devuelve el nombre del permiso.
     * @return string
     */
    public function getPermiso()
    {
        $permiso = new Permisos();
        $permiso->readOne(['id', $this->permiso_id]);
        return $permiso->permiso;
        ;
    }

    public function getActividad($config)
    {
        $limit = false;
        $offset = false;
        extract($config);
        $query = QueryBuilder::db($this->conn)
        ->select('*')
        ->from('actividad_reciente')
        ->where(['created_by', $this->nombre])
        ->limit($limit)
        ->offset($offset)
        ->get();

        return $query;
    }

    /**
     * Devuelve todos los roles en un array del siguiente formato:
     *  [
     *      'id' => 'nombre',
     *      'id' => 'nombre',
     *  ]
     * @param  bool     $withEmpty  Determina si se añade un valor "vacío" que
     * sería [''] => 'Ningún' con el propósito de servir para una lista
     * desplegable.
     * @return array                Valores en el formato ya citado arriba.
     */
    public static function getAll($withEmpty = false)
    {
        $db = new Database();
        $db = $db->getConnection();
        $query = QueryBuilder::db($db)
            ->select('id, nombre')
            ->from('roles')
            ->get();

        $data = $query->fetchAll();

        $new = [];
        if ($withEmpty) {
            $new[''] = 'Ningún';
        }
        foreach ($data as $value) {
            $new[$value['id']] = $value['nombre'];
        }

        asort($new, SORT_NATURAL | SORT_FLAG_CASE);

        return $new;
    }
}
