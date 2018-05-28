<?php

namespace utilities\query;

use PDO;
use PDOStatement;

/**
 * @author María Valderrama Rodríguez <contact@mavalroot.es>
 * @copyright Copyright (c) 2018, María Valderrama Rodríguez
 *
 * Clase que ayuda a la construcción de una query.
 */
class QueryBuilder
{
    /**
     * Instancia de la clase Html que será devuelta para poder encadenar
     * métodos.
     * @var QueryBuilder
     */
    private static $_instance = null;

    /**
     * Conexión a la base de datos.
     * @var null|PDO
     */
    private static $conn = null;

    /**
     * Cláusula select de la query.
     * @var string
     */
    private static $select = '*';

    /**
     * Cláusula from de la query.
     * @var bool|string
     */
    private static $from = false;

    /**
     * Cláusulas join de la query.
     * @var array
     *
     * Tiene el siguiente formato:
     */
    private static $join = [];

    /**
     * Cláusulas order de la query.
     * @var array
     *
     * Tiene el siguiente formato:
     */
    private static $order = [];

    /**
     * Cláusulas where de la query.
     * @var array
     *
     * Tiene el siguiente formato:
     */
    private static $where = [];

    /**
     * Cláusulas group de la query.
     * @var array
     *
     * Tiene el siguiente formato:
     */
    private static $group = [];

    /**
     * Cláusulas having de la query.
     * @var array
     *
     * Tiene el siguiente formato:
     */
    private static $having = [];

    /**
     * Cláusula offset de la query.
     * @var bool|int
     */
    private static $offset = false;

    /**
     * Cláusula limit de la query.
     * @var bool|int
     */
    private static $limit = false;

    /**
     * Los valores que serán bindeados antes de ejecutar la query.
     * @var array
     *
     * Tiene el siguiente formato:
     */
    private static $values = [];

    /**
     * El constructor es privado para que no se instancien elementos de
     * la clase.
     */
    private function __construct()
    {
    }

    /**
     * Devuelve la instancia de clase para poder encadenar métodos.
     * @return QueryBuilder
     */
    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    /**
     * Proporciona una conexión a la base de datos deseada para hacer la query.
     * Es obligatorio.
     *
     * @param  PDO          $db
     * @return QueryBuilder
     */
    public static function db($db)
    {
        // $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        self::$conn = $db;
        return static::getInstance();
    }

    /**
     * Indica la select de la query que se está construyendo.
     * Es opcional, pues en caso de que no se proporcione será '*'.
     *
     * @param  string       $value  Valor de la select.
     * @return QueryBuilder
     */
    public static function select($value)
    {
        self::$select = $value;
        return static::getInstance();
    }

    /**
     * Indica el from de la query que se está construyendo.
     * Es obligatorio.
     * @param  string       $value Nombre de la tabla.
     * @return QueryBuilder
     */
    public static function from($value)
    {
        self::$from = $value;
        return static::getInstance();
    }

    /**
     * Indica el join de la query que se está construyendo.
     * @param  string       $value Nombre de la tabla.
     * @param  array        $array Columnas de la tabla del from y la tabla
     * de join que estarán unidas por la cláusula ON.
     *
     * Ejemplo:
     * [...]
     * ->from('tabla1')
     * ->join('tabla2', ['tabla1.columna1', 'tabla2.columna2'])
     *[...]
     *
     * @return QueryBuilder
     */
    public static function join($value, $array)
    {
        self::$join[] = [
            'type' => 'JOIN',
            'join' => $value,
            'on' => $array,
        ];
        return static::getInstance();
    }

    /**
     * Indica el left join de la query que se está construyendo.
     * @param  string       $value Nombre de la tabla.
     * @param  array        $array Columnas de la tabla del from y la tabla
     * de join que estarán unidas por la cláusula ON.
     *
     * Ejemplo:
     * [...]
     * ->from('tabla1')
     * ->leftJoin('tabla2', ['tabla1.columna1', 'tabla2.columna2'])
     *[...]
     *
     * @return QueryBuilder
     */
    public static function leftJoin($value, $array)
    {
        self::$join[] = [
            'type' => 'LEFT JOIN',
            'join' => $value,
            'on' => $array,
        ];
        return static::getInstance();
    }

    /**
     * Indica el right join de la query que se está construyendo.
     * @param  string       $value Nombre de la tabla.
     * @param  array        $array Columnas de la tabla del from y la tabla
     * de join que estarán unidas por la cláusula ON.
     *
     * Ejemplo:
     * [...]
     * ->from('tabla1')
     * ->rightJoin('tabla2', ['tabla1.columna1', 'tabla2.columna2'])
     *[...]
     *
     * @return QueryBuilder
     */
    public static function rightJoin($value, $array)
    {
        self::$join[] = [
            'type' => 'RIGHT JOIN',
            'join' => $value,
            'on' => $array,
        ];
        return static::getInstance();
    }

    /**
     * Indica los order de la query que se está construyendo.
     * @param  string|array  $order Puede ser string o un array con varios.
     *
     * Ejemplo:
     * [...]
     * ->orderBy('columna DESC/ASC')
     * [...]
     *
     * [...]
     * ->orderBy(['columna1 DESC/ASC', 'columna2 DESC/ASC'])
     * [...]
     * @return QueryBuilder
     */
    public static function orderBy($order)
    {
        if (is_string($order)) {
            self::$order[] = $order;
        } elseif (is_array($order)) {
            foreach ($order as $value) {
                self::$order[] = $value;
            }
        }
        return static::getInstance();
    }

    /**
     * Indica el where de la query que se está construyendo.
     * @param  array        $array Contiene columna y valor. Opcionalmente
     * el operador que se utiliza para comparar. Por defecto es '='.
     *
     * Ejemplo:
     * [...]
     * ->where(['columna', 'valor', 'operador'])
     * [...]
     *
     * @return QueryBuilder
     */
    public static function where($array)
    {
        self::$where[] = [
            'type' => 'WHERE',
            'where' => $array[0],
            'value' => $array[1],
            'op' => (isset($array[2]) ? $array[2] : '='),
        ];
        return static::getInstance();
    }

    /**
     * Indica el where de la query que se está construyendo. La diferencia con
     * where() es que este añade un 'and' al principio.
     * @param  array        $array Contiene columna y valor. Opcionalmente
     * el operador que se utiliza para comparar. Por defecto es '='.
     *
     * Ejemplo:
     * [...]
     * ->where(['columna', 'valor', 'operador'])
     * [...]
     *
     * @return QueryBuilder
     */
    public static function andWhere($array)
    {
        self::$where[] = [
            'type' => 'AND WHERE',
            'where' => $array[0],
            'value' => $array[1],
            'op' => (isset($array[2]) ? $array[2] : '='),
        ];
        return static::getInstance();
    }

    /**
     * Indica el where de la query que se está construyendo. La diferencia con
     * where() es que este añade un 'or' al principio.
     * @param  array        $array Contiene columna y valor. Opcionalmente
     * el operador que se utiliza para comparar. Por defecto es '='.
     *
     * Ejemplo:
     * [...]
     * ->orWhere(['columna', 'valor', 'operador'])
     * [...]
     *
     * @return QueryBuilder
     */
    public static function orWhere($array)
    {
        self::$where[] = [
            'type' => 'OR WHERE',
            'where' => $array[0],
            'value' => $array[1],
            'op' => (isset($array[2]) ? $array[2] : '='),
        ];
        return static::getInstance();
    }

    /**
     * Indica el limit de la query que se está construyendo.
     * @param  int|bool     $value
     * @return QueryBuilder
     */
    public static function limit($value)
    {
        self::$limit = $value;
        return static::getInstance();
    }

    /**
     * Indica el offset de la query que se está construyendo.
     * @param  int|bool     $value
     * @return QueryBuilder
     */
    public static function offset($value)
    {
        self::$offset = $value;
        return static::getInstance();
    }

    /**
     * Indica el group by de la query que se está construyendo.
     * @param  array        $array Array con los nombres de las columnas por
     * los que se agrupa la query.
     *
     * Ejemplo:
     * [...]
     * ->groupBy(['columna1', 'columna2', 'columna3'])
     * [...]
     * @return QueryBuilder
     */
    public static function groupBy($array)
    {
        self::$group = $array;
        return static::getInstance();
    }

    /**
     * Indica el having de la query que se está construyendo.
     * @param  array        $array Contiene columna y valor. Opcionalmente
     * el operador que se utiliza para comparar. Por defecto es '='.
     *
     * Ejemplo:
     * [...]
     * ->having(['columna', 'valor', 'operador'])
     * [...]
     *
     * @return QueryBuilder
     */
    public static function having($array)
    {
        self::$having[] = [
            'type' => 'HAVING',
            'having' => $array[0],
            'value' => $array[1],
            'op' => (isset($array[2]) ? $array[2] : '='),
        ];
        return static::getInstance();
    }

    /**
     * Indica el having de la query que se está construyendo. La diferencia con
     * having() es que este añade un 'and' al principio.
     * @param  array        $array Contiene columna y valor. Opcionalmente
     * el operador que se utiliza para comparar. Por defecto es '='.
     *
     * Ejemplo:
     * [...]
     * ->andHaving(['columna', 'valor', 'operador'])
     * [...]
     *
     * @return QueryBuilder
     */
    public static function andHaving($array)
    {
        self::$having[] = [
            'type' => 'AND HAVING',
            'having' => $array[0],
            'value' => $array[1],
            'op' => (isset($array[2]) ? $array[2] : '='),
        ];
        return static::getInstance();
    }

    /**
     * Indica el having de la query que se está construyendo. La diferencia con
     * having() es que este añade un 'or' al principio.
     * @param  array        $array Contiene columna y valor. Opcionalmente
     * el operador que se utiliza para comparar. Por defecto es '='.
     *
     * Ejemplo:
     * [...]
     * ->orHaving(['columna', 'valor', 'operador'])
     * [...]
     *
     * @return QueryBuilder
     */
    public static function orHaving($array)
    {
        self::$having[] = [
            'type' => 'OR HAVING',
            'having' => $array[0],
            'value' => $array[1],
            'op' => (isset($array[2]) ? $array[2] : '='),
        ];
        return static::getInstance();
    }

    /**
     * Una vez formada la query, este método la bindea, la ejecuta y por
     * último la devuelve.
     * @return PDOStatement
     */
    public static function get()
    {
        $query = self::createQuery();
        $query = self::$conn->prepare($query);
        self::bind($query);
        $query->execute();
        self::default();
        return $query;
    }

    /**
     * Construye la query con las propiedades que se han ido especificando.
     * @return string
     */
    private function createQuery()
    {
        $query = '';

        $query .= 'SELECT ' . self::$select . ' ';
        if (isset(self::$from)) {
            $query .= 'FROM ' . self::$from . ' ';
        } else {
            return false;
        }

        if (self::$join) {
            foreach (self::$join as $value) {
                $query .= $value['type'] . ' ' . $value['join'] . ' ON ' . $value['on'][0] . ' = ' . $value['on'][1] . ' ';
            }
        }

        if (self::$where) {
            foreach (self::$where as $value) {
                if ($value['value']) {
                    $query .= $value['type'] . ' ' . $value['where'] . ' ' . $value['op'] . ' ? ';
                    self::$values[] = $value['value'];
                }
            }
        }

        if (self::$group) {
            $query .= 'GROUP BY ' . implode(', ', self::$group) . ' ';
        }

        if (self::$having) {
            foreach (self::$having as $value) {
                $query .= $value['type'] . ' ? = ? ';
            }
        }

        if (self::$order) {
            $query .= 'ORDER BY ' . implode(self::$order) . ' ';
        }

        if (self::$limit) {
            $query .= 'LIMIT ' . self::$limit . ' ';
        }

        if (self::$offset) {
            $query .= 'OFFSET ' . self::$offset . ' ';
        }

        return $query;
    }

    /**
     * Bindea los parámetros para las cláusulas where, having, y otras, para
     * la query.
     * @param  string $query Query a la que se le bindearán los parámetros.
     */
    private function bind(&$query)
    {
        foreach (self::$values as $index => $param) {
            $query->bindParam($index+1, $param);
        }
    }

    /**
     * Devuelve el estado de QueryBuilder al por defecto.
     */
    private function default()
    {
        self::$conn = null;
        self::$select = '*';
        self::$from = false;
        self::$join = [];
        self::$order = [];
        self::$where = [];
        self::$group = [];
        self::$having = [];
        self::$offset = false;
        self::$limit = false;
        self::$values = [];
    }
}
