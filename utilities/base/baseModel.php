<?php

namespace utilities\base;

use PDO;
use models\Historial;

use utilities\base\Database;
use utilities\helpers\html\Html;
use utilities\helpers\validation\ModelValidator;
use utilities\query\QueryBuilder;

/**
 * @author María Valderrama Rodríguez <contact@mavalroot.es>
 * @copyright Copyright (c) 2018, María Valderrama Rodríguez
 *
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
     * Errores obtenidos al hacer un validate().
     * @var array
     */
    protected $errors = [];

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
    public function rules()
    {
        return;
    }

    /**
     * Labels relacionados con la propiedad de la clase, con motivo de su mejor
     * visualización en las vistas.
     * @return array En formato clave => valor donde "clave" es la propiedad y
     * "valor" el label.
     * Por ejemplo:
     *  [
     *      'id' => 'Id de la clase',
     *      'nombre' => 'Nombre completo',
     *      'created_at' => 'Fecha de creación',
     *  ]
     */
    protected static function labels()
    {
        return [

        ];
    }

    /**
     * Devuelve el label de una propiedad en específico.
     * @param  string $prop Propiedad de la que se desea obtener el label.
     * @return string       Label de la propiedad proporcionada.
     */
    public static function getLabel($prop)
    {
        $labels = static::labels();
        return isset($labels[$prop]) ? $labels[$prop] : false;
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
        $this->beforeInsert();
        $columnas = static::getAllColumns();
        $datos = [];
        foreach ($columnas as $value) {
            if (isset($this->$value) && ($this->$value == true || $this->$value == '0')) {
                $datos[$value] = $this->$value;
            }
        }
        $query =    "INSERT INTO
                        " . static::tableName() . "
                    (" . implode(', ', array_keys($datos)) . ")
                    VALUES
                    (" . implode(', ', preg_filter('/^/', ':', array_keys($datos))) . ")";

        $stmt = $this->conn->prepare($query);
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
    protected function actionMessages()
    {
        return [
            'insert' => 'Ha creado un registro.',
            'update' => 'Ha hecho modificaciones en un registro.',
            'delete' => 'Ha borrado un registro.',
        ];
    }

    /**
     * Devuelve un mensaje de acción en concreto.
     * @param  string $action   Puede ser insert, update o delete.
     * @return string           El mensaje si este fuera válido o cadena vacía
     * si no.
     */
    public function getActionMessage($action)
    {
        $messages = $this->actionMessages();
        return isset($messages[$action]) ? $messages[$action] : '';
    }

    /**
     * Crea un record para el regisro de actividad reciente. La idea es que
     * se cree uno cuando se hace insert, update o delete.
     * @param  string $action Debe ser insert, update o delete.
     */
    public function createRecord($action)
    {
        $record = new Historial([
            'accion' => $this->getActionMessage($action),
            'tipo' => $this->tableName(),
            'referencia' => ($action != 'delete' ? $this->id : ''),
            'created_by' => $_SESSION['id']
        ]);

        return $record->validate() && $record->create();
    }

    /**
     * Devuelve los datos del modelo en formato string, con sus correspondientes
     * saltos de linea, para crear un código QR que muestre dicho texto.
     * @param  array  $exclude  Array que recoge los valores del modelo que no
     * se incluirán en el QR, como por ejemplo: ['id', 'created_at'].
     * @return string           string con los datos para el código QR.
     */
    public function getQRData($exclude = [])
    {
        $data = '';
        $columnas = $this->getAllColumns();
        foreach ($columnas as $columna) {
            if (in_array($columna, $exclude)) {
                continue;
            }
            $data .= ($this->getLabel($columna) ?: $columna) . ': ' . (isset($this->$columna) ? $this->$columna : '') . ".\n";
        }
        return Html::h($data);
    }
}
