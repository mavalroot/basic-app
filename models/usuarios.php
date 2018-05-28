<?php

namespace models;

use PDO;

use utilities\base\Database;
use utilities\base\BaseModel;
use utilities\query\QueryBuilder;

/**
 * Esta clase representa a los usuarios de la tabla usuarios.
 * Se usará para login y logout.
 *
 * {@inheritdoc}
 */
class Usuarios extends BaseModel
{

    /**
     * Nombre de usuario, que se corresponde al nombre de usuario de la tabla.
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
        return 'usuarios';
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
            'permiso_id' => 'Permisos',
            'password_hash' => 'Contraseña'
        ];
    }

    public function rules()
    {
        return [
            [['nombre', 'password_hash'], 'required'],
            [['permiso_id'], 'in', array_values(Permisos::getAll())],
            [['nombre'], 'max', 25, 'message' => 'Error: el nombre de usuario debe ser como máximo de 25 carácteres.'],
        ];
    }

    protected function beforeInsert()
    {
        $this->password_hash = password_hash($this->password_hash, PASSWORD_DEFAULT);
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
     * Comprueba si el usuario actual está loggeado.
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

    /**
     * Devuelve todos los usuarios en un array del siguiente formato:
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

        asort($new, SORT_NATURAL | SORT_FLAG_CASE);

        return $new;
    }

    /**
     * Devuelve las entradas del historial relacionados con este usuario.
     * @param  array $config Configuración adicional, puede ser: limit, offset.
     */
    public function getHistorial($config)
    {
        $limit = false;
        $offset = false;
        extract($config, EXTR_IF_EXISTS);
        $query = QueryBuilder::db($this->conn)
        ->select('*')
        ->from('historial')
        ->where(['created_by', $this->id])
        ->limit($limit)
        ->offset($offset)
        ->orderBy('created_at DESC')
        ->get();

        return $query;
    }
}
