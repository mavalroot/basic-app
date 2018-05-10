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
    public $password;

    protected $columnas = [
        'Última conexión' => 'last_conn',
    ];

    protected $sortBy = 'last_conn DESC';

    protected $searchBy = ['Nombre de rol' => 'nombre'];

    public static function tableName()
    {
        return 'roles';
    }

    public static function primaryKey()
    {
        return 'nombre';
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
        $password = $row['password'];

        if (password_verify($this->password, $password)) {
            $_SESSION['rol'] = $this->nombre;
            $_SESSION['rol'] = $this->getRole();
            $this->readOne(['nombre', $this->nombre]);
            $this->last_conn = date("Y-m-d H:i:s");
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
        if (isset($_SESSION['rol'])) {
            return $this->readOne(['nombre', $_SESSION['rol']]);
        }
        return false;
    }

    /**
     * Devuelve el rol del rol.
     * @return string El rol.
     */
    public function getRole()
    {
        $query = QueryBuilder::db($this->conn)
        ->select('*')
        ->from('roles u')
        ->join('roles r', ['u.rol_id', 'r.id'])
        ->where(['nombre', $this->nombre])
        ->get();

        $row = $query->fetch(PDO::FETCH_ASSOC);
        return $row['tipo'];
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
