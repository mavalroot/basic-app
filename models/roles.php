<?php

namespace models;

use PDO;

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

    protected $columnas = [
        'Última conexión' => 'last_con',
    ];

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

    public function getRoleName()
    {
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
}
