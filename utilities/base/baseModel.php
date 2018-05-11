<?php

namespace utilities\base;

use PDO;
use models\ActividadReciente;

use utilities\base\Database;
use utilities\helpers\html\Html;
use utilities\helpers\validation\ModelValidator;
use utilities\query\QueryBuilder;

/**
 * Modelo básico de clase.
 */
class BaseModel
{
    /**
     * Conexión a la base de datos.
     * @var null|PDO
     */
    protected $conn;

    /**
     * Campos de búsqueda. Sigue el siguiente formato:
     * [
     *      'Label' => 'columna1',
     *      'Label' => 'columna2',
     * ]
     * @var array
     */
    protected $searchBy = ['ID' => 'id'];

    /**
     * Atributo por el cual se ordenan las columnas de la tabla.
     * Primer se indica la columa, luego si la ordenación es ascendente (ASC)
     * o descendente (DESC).
     * @var string
     */
    protected $sortBy = 'created_at DESC';

    /**
     * Array de parejas clave => valor.
     * La clave es utilizado como label, y el valor se utiliza para acceder a
     * los atributos.
     * Es muy importante que esté bien formado, y que los valores tengan el
     * mismo nombre que la columna en la tabla.
     * @var array
     */
    protected $columnas;

    /**
     * Errores obtenidos al hacer un validate().
     * @var array
     */
    protected $errors = [];

    /**
     * Mensajes para la creación de acciones del historial. Se puede
     * personalizar en cada clase o dejar el estandar definido a continuación.
     * @var array
     *
     * Sigue el siguiente formato:
     * [
     *      'accion' => 'mensaje'
     * ]
     *
     * Las acciones deben ser: insert, update y delete
     */
    protected $actionMessage = [
        'insert' => 'Ha creado un registro.',
        'update' => 'Ha hecho modificaciones en un registro.',
        'delete' => 'Ha marcado para borrar un registro.',
    ];

    /**
     * Constructor de nuestra clase.
     * @param array $config Es un array clave => valor opcional, que se le
     * podrá pasar al modelo para incializar sus propiedades.
     *
     * Ejemplo:
     * $ejemplo = new BaseModel(['nombre de la propiedad' => valor]);
     *
     */
    public function __construct($config = [])
    {
        $database = new Database();
        $db = $database->getConnection();
        $this->conn = $db;

        if ($config) {
            foreach ($config as $propiedad => $valor) {
                $this->$propiedad = $valor;
            }
        }
    }

    /**
     * Nombre de la tabla asociada a la clase.
     * @return string
     */
    public static function tableName()
    {
        return;
    }

    /**
     * Clave primaria por la cual se indexa la tabla.
     * Debe corresponder al nombre de una columna de la tabla que corresponde
     * a la clase.
     * @return string
     */
    public static function primaryKey()
    {
        return 'id';
    }

    /**
     * Es un array que contiene las normas de validación para comprobar, antes
     * de intentar insertar una fila en la tabla, que la instancia de clase
     * es una fila válida.
     * En caso de que haya que insertar filas en varias tablas de forma
     * encadenada, ya sea porque se relacionen entre si o por otro motivo,
     * lograremos que si una de esas inserciones no es válida, no se inserten
     * las anteriores.
     *
     * Para construir las reglas de la clase hay que seguir la siguiente
     * estructura:
     *
     * Comprobar tipo:
     * [
     *      ['propiedad_de_la_clase', 'tipo', 'message' => 'Mensaje de error']
     * ]
     * Téngase en cuenta que el mensaje es opcional, ya que hay uno por defecto.
     *
     * Operaciones
     * [
     *      ['propiedad_de_la_clase', 'operador', 'comparacion', 'message' => 'Mensaje de error']
     * ]
     * Téngase en cuenta que el mensaje es opcional, ya que hay uno por defecto.
     *
     * Ejemplo:
     * return [
     *      ['edad', integer],
     *      ['edad', '>', 0, 'message' => 'La edad debe ser mayor que cero'],
     * ];
     *
     * @return array
     */
    public static function rules()
    {
        return;
    }

    /**
     * Valida las propiedades del modelo para comprobar que cumple las reglas
     * previamente definidas en rules().
     *
     * Se utiliza la clase ModelValidator.
     *
     * Los errores generados se guardan en el atributo $errors, y pueden ser
     * consultados a través de getErrors().
     *
     * @return bool Devuelve true si el modelo ha validado correctamente, o
     * false si no lo ha hecho.
     */
    public function validate()
    {
        $this->errors = [];
        $this->errors = ModelValidator::validate($this);
        return count($this->errors) == 0;
    }

    /**
     * Devuelve los errores guardados en el atributo $errors.
     * @param string $attr  Especifica el error de qué campo quiere mostrar.
     * @return array|string
     */
    public function getErrors($attr = null)
    {
        if (isset($attr)) {
            return isset($this->errors[$attr]) ? $this->errors[$attr] : '';
        }
        return $this->errors;
    }

    /**
     * Devuelve el valor del atributo $columnas
     * @return array
     */
    public function getColumnas()
    {
        return $this->columnas;
    }

    /**
     * Cambia el atributo columnas para esa instancia de la clase.
     *
     * @param array $columnas Debe ser un array con parejas clave => valor,
     * donde clave es el label para mostrar y valor el valor de la columna.
     */
    public function setColumnas($columnas)
    {
        if (is_array($columnas)) {
            $this->columnas = $columnas;
        }
    }

    /**
     * Devuelve el atributo searchBy.
     * @return array
     */
    public function getSearchBy()
    {
        return $this->searchBy;
    }

    /**
     * Cambia el criterio de búsqueda de una instancia.
     * @param string $new
     */
    public function setSortBy($new)
    {
        $this->sortBy = $new;
    }

    /**
     * Se implementa antes del insert.
     * @return mixed
     */
    protected function beforeInsert()
    {
        return;
    }

    /**
     * Se implementa después del insert.
     * @return mixed
     */
    protected function afterInsert()
    {
        return;
    }

    /**
     * Se implementa antes del update.
     * @return mixed
     */
    protected function beforeUpdate()
    {
        return;
    }

    /**
     * Se implementa después del update.
     * @return mixed
     */
    protected function afterUpdate()
    {
        return;
    }

    /**
     * Carga los datos obtenidos por post al modelo.
     * @param  array $post
     */
    public function load($post)
    {
        foreach ($post as $clave => $valor) {
            $clave = Html::h($clave);
            $valor = Html::h($valor);
            $this->$clave = $valor;
        }
    }

    /**
     * Muestra una fila específica de la tabla.
     * Por defecto usará la clave primaria, pero si se le pasa un array que
     * contenga "columna" y "valor" lo usará para filtrar.
     *
     * $ejemplo->readOne(['columna' => 'valor']);
     * $ejemplo->readOne();
     *
     * @param array $where
     */
    public function readOne($where = false)
    {
        if (count($where) != 2) {
            $where = false;
        }
        $param = $where[1] ?: $this->{static::primaryKey()};
        $column = $where[0] ?: static::primaryKey();
        $query = QueryBuilder::db($this->conn)
        ->from(static::tableName())
        ->where([$column, $param])
        ->get();
        $row = $query->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            foreach ($row as $key => $value) {
                $this->$key = $row[$key];
            }
            return true;
        }
        return false;
    }

    /**
     * Va mostrando todas las filas de una tabla, con unas limitaciones para
     * la paginación.
     * @param array $config Configuración.
     *
     * Espera recibir:
     * string   $config['searchTerm']   -> Término de búsqueda.
     * string   $config['searchBy']     -> Columna por la cual se busca.
     * int      $config['limit']        -> Limit.
     * int      $config['offset']       -> Offset.
     */
    public function readAll($config)
    {
        $searchBy = '';
        $limit = false;
        $offset = false;
        extract($config);
        $searchTerm = isset($searchTerm) && $searchTerm != '' ? "%{$searchTerm}%" : '';

        $query = QueryBuilder::db($this->conn)
            ->from(static::tableName())
            ->orderBy($this->sortBy)
            ->where([$searchBy, $searchTerm, 'ilike'])
            ->limit($limit)
            ->offset($offset)
            ->get();
        return $query;
    }

    /**
     * Crea una nueva fila en la tabla.
     * @return bool Indica si se ha insertado correctamente o si ha fallado.
     */
    public function create()
    {
        $columnas = static::getAllColumns();
        $datos = [];
        foreach ($columnas as $value) {
            if (isset($this->$value) && $this->$value == true) {
                $datos[$value] = $this->$value;
            }
        }
        $query =    "INSERT INTO
                        " . static::tableName() . "
                    (" . implode(', ', array_keys($datos)) . ")
                    VALUES
                    (" . implode(', ', preg_filter('/^/', ':', array_keys($datos))) . ")";

        $stmt = $this->conn->prepare($query);
        $this->beforeInsert();
        if ($stmt->execute($datos)) {
            $this->id = $this->conn->lastInsertId();
            $this->afterInsert();
            return true;
        }
        return false;
    }

    /**
     * Actualiza una fila de la tabla.
     * @return bool Indica si se ha conseguido o no.
     */
    public function update()
    {
        $this->beforeUpdate();
        $columnas = static::getAllColumns();
        $datos = [];
        foreach ($columnas as $value) {
            if (isset($this->$value) && $this->$value == true) {
                $datos[$value] = $this->$value;
            }
        }

        foreach ($datos as $key => $valor) {
            $query = "UPDATE
                        " . static::tableName() . "
                    SET
                        $key = :{$key}
                    WHERE
                        " . static::primaryKey() . " = :" . static::primaryKey();

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':' . $key, $valor);
            $stmt->bindParam(':' . static::primaryKey(), $this->{static::primaryKey()});
            if (!$stmt->execute()) {
                return false;
            }
        }
        $this->afterUpdate();
        return true;
    }

    /**
     * Elimina una fila de la tabla.
     * @return bool Indica si se ha conseguido o no.
     */
    public function delete()
    {
        $query = "DELETE FROM " . static::tableName() . " WHERE " . static::primaryKey() . " = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->{static::primaryKey()});

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Resetea la instancia del objeto.
     */
    public function reset()
    {
        foreach ($this as $clave => $valor) {
            unset($this->$clave);
        }
    }

    /**
     * Cuenta todas las filas de una tabla, sirve para la paginación.
     * @param array $search Parámetros para la búsqueda
     *
     * string $search['searchTerm']    Término de búsqueda.
     * string $search['searchBy']      Columna por la cual se busca.
     */
    public function countAll($search = [])
    {
        $searchBy = '';
        extract($search);
        $searchTerm = isset($searchTerm) && $searchTerm != '' ? "%{$searchTerm}%" : '';

        $query = QueryBuilder::db($this->conn)
        ->select('count(*) as rows')
        ->from(static::tableName())
        ->where([$searchBy, $searchTerm, 'ilike'])
        ->get();

        $row = $query->fetch(PDO::FETCH_ASSOC);
        return $row['rows'];
    }

    /**
     * Crea un record para el regisro de actividad reciente. La idea es que
     * se cree uno cuando se hace insert, update o delete.
     * @param  string $action Debe ser insert, update o delete.
     */
    public function createRecord($action)
    {
        // $record = new ActividadReciente([
        //     'accion' => $this->actionMessage[$action],
        //     'tipo' => $this->tableName(),
        //     'referencia' => $this->id,
        //     'created_by' => $_SESSION['rol']
        // ]);
        // $record->create();
        return;
    }

    /**
     * Obtiene los nombres de las columnas de la tabla en forma de array.
     * @return array
     */
    public static function getAllColumns()
    {
        $conection = new Database();
        $db = $conection->getConnection();
        $query = QueryBuilder::db($db)
        ->select('*')
        ->from(static::tableName())
        ->get();
        $i = 0;
        $columns = [];
        while ($val = $query->getColumnMeta($i)) {
            $columns[] = $val['name'];
            $i++;
        }
        return $columns;
    }
}
