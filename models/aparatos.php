<?php

namespace models;

use utilities\base\BaseModel;
use utilities\base\Database;

use utilities\query\QueryBuilder;

/**
 *
 */
class Aparatos extends BaseModel
{
    protected $searchBy = [
        'Número de serie' => 'num_serie',
        'Tipo de aparato' => 'tipo',
        'Marca' => 'marca',
        'Modelo' => 'modelo',
    ];

    public static function tableName()
    {
        return 'aparatos';
    }

    public function rules()
    {
        return [
            [['usuario_id'], 'in', array_keys(Usuarios::getAll(true)), 'message' => 'Error: Seleccione una opción válida.'],
            [['delegacion_id'], 'in', array_keys(Delegaciones::getAll(true)), 'message' => 'Error: Seleccione una opción válida.'],
            [['tipo'], 'in', array_keys(static::getTypes()), 'message' => 'Error: Seleccione una opción válida.']
        ];
    }

    protected static function labels()
    {
        return [
            'usuario_id' => 'Utilizado por el usuario',
            'delegacion_id' => 'Utilizado por la delegación',
            'marca' => 'Marca',
            'modelo' => 'Modelo',
            'num_serie' => 'Número de serie',
            'fecha_compra' => 'Fecha de compra',
            'proveedor' => 'Proveedor',
            'tipo' => 'Tipo de aparato',
            'observaciones' => 'Observaciones',
        ];
    }

    protected function actionMessages()
    {
        return [
            'insert' => 'Ha registrado "' . $this->getFullName() . '".',
            'update' => 'Ha hecho modificaciones "' . $this->getFullName() . '".',
            'delete' => 'Ha borrado "' . $this->getFullName() . '".',
            'cambiar' => 'Ha cambiado el usuario de "' . $this->getFullName() . '" por "' . $this->getNombreUsuario() . '".',
        ];
    }
    /**
     * Devuelve el modelo secundario que está relacionado con este modelo de
     * aparatos, relleno con sus datos correspondientes.
     * @return ElectronicaRed|Impresoras|Monitores|Ordenadores|Perifericos|null
     */
    public function getDatosAsociados()
    {
        $model = $this->getModelByType();
        $model->aparato_id = $this->id;
        if ($model->readOne()) {
            return $model;
        }
        return null;
    }

    /**
     * Devuelve un modelo vacío dependiendo del tipo del aparato, que
     * corresponderá con su modelo secundario.
     * @return ElectronicaRed|Impresoras|Monitores|Ordenadores|Perifericos|null
     */
    public function getModelByType()
    {
        $id = isset($this->id) ? ['aparato_id' => $this->id] : false;

        switch ($this->tipo) {
            case 'ordenadores':
                return new Ordenadores($id);
            case 'perifericos':
                return new Perifericos($id);
            case 'impresoras':
                return new Impresoras($id);
            case 'electronica_red':
                return new ElectronicaRed($id);
            case 'monitores':
                return new Monitores($id);
            default:
                return null;
        }
    }

    /**
     * Devuelve los tipos posibles.
     * @return array Valores en formato clave => valor donde "clave" es el
     * valor que se guarda en tabla y "valor" el label.
     */
    public static function getTypes()
    {
        return [
            'ordenadores' => 'Ordenadores',
            'impresoras' => 'Impresoras',
            'monitores' => 'Monitores',
            'perifericos' => 'Periféricos',
            'electronica_red' => 'Electrónica de red',
        ];
    }

    /**
     * Devuelve todos los aparatos en un array del siguiente formato:
     *  [
     *      'id' => 'num_serie',
     *      'id' => 'num_serie',
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
            ->select('id, num_serie')
            ->from('aparatos')
            ->get();

        $data = $query->fetchAll();

        $new = [];
        if ($withEmpty) {
            $new[''] = 'Ningún';
        }
        foreach ($data as $value) {
            $new[$value['id']] = $value['num_serie'];
        }

        return $new;
    }

    /**
     * Devuelve el modelo del usuario que actualmente está usando este aparato.
     * @return Usuarios|null    Usuario con sus datos correspondientes, o null
     * en caso de que el aparato no tenga un usuario asignado.
     */
    public function getUsuario()
    {
        if ($this->usuario_id) {
            $usuario = new Usuarios([
                'id' => $this->usuario_id
            ]);
            if ($usuario->readOne()) {
                return $usuario;
            }
        }
        return null;
    }

    /**
     * Devuelve el nombre de usuario (si lo hubiera) que corresponde al
     * usuario que actualmente está usando este aparato.
     * @return string   Nombre del usuario en formato string o cadena vacía
     * en el caso de que el aparato no tenga un usuario asignado.
     */
    public function getNombreUsuario()
    {
        $user = $this->getUsuario();
        return isset($user->nombre) ? $user->nombre : '';
    }

    /**
     * Devuelve el modelo de la delegación que actualmente está usando este
     * aparato.
     * @return Delegaciones|null    Delegacion con sus datos correspondientes, o
     * null en caso de que el aparato no tenga una delegación asignada.
     */
    public function getDelegacion()
    {
        if ($this->delegacion_id) {
            $usuario = new Delegaciones([
                'id' => $this->delegacion_id
            ]);
            if ($usuario->readOne()) {
                return $usuario;
            }
        }
        return null;
    }

    /**
     * Devuelve el nombre de la delegación (si la hubiera) que corresponde a la
     * delegación que actualmente está usando este aparato.
     * @return string   Nombre de la delegación en formato string o cadena vacía
     * en el caso de que el aparato no tenga una delegación asignada.
     */
    public function getNombreDelegacion()
    {
        $delegacion = $this->getDelegacion();
        return isset($delegacion->nombre) ? $delegacion->nombre : '';
    }

    /**
     * Devuelve un array con los usuarios que anteriormente usaron este aparato,
     * según los datos guardados en la tabla "aparatos_usuarios".
     * @return array    Array con los datos necesarios, en el siguiente formato:
     *  [
     *      [
     *          'propiedad1' => 'valor', 'propiedad2' => 'valor', ...
     *      ],
     *      [
     *          'propiedad1' => 'valor', 'propiedad2' => 'valor', ...
     *      ],
     *  ]
     */
    public function getUsuariosAnteriores()
    {
        $query = QueryBuilder::db($this->conn)
        ->select('au.*, u.nombre')
        ->from('aparatos_usuarios au')
        ->join('usuarios u', ['au.usuario_id', 'u.id'])
        ->where(['aparato_id', $this->id])
        ->orderBy(['created_at DESC'])
        ->get();

        return $query->fetchAll();
    }

    /**
     * Devuelve el tipo del aparato en singular y bien formateado, con idea de
     * usarlo para visualizar.
     * @return string
     */
    public function getTipoSingular()
    {
        switch ($this->tipo) {
            case 'ordenadores':
                return 'Ordenador';
            case 'perifericos':
                return 'Periférico';
            case 'impresoras':
                return 'Impresora';
            case 'electronica_red':
                return 'Electrónica de Red';
            case 'monitores':
                return 'Monitor';
            default:
                return '';
        }
    }

    public function getQRData($exclude = [])
    {
        $data = 'Usuario actual: '
        . $this->getNombreUsuario() . ".\nDelegación: "
        . $this->getNombreDelegacion() . ".\n";

        $data = parent::getQRData(['id', 'usuario_id', 'delegacion_id', 'created_at']);
        return $data;
    }

    public function getFullName()
    {
        return $this->getTipoSingular() . ' ' . $this->marca . ' ' . $this->modelo;
    }
}
