<?php

namespace models;

use utilities\base\Database;
use utilities\base\BaseModel;

use utilities\query\QueryBuilder;

/**
 *

 */
class Usuarios extends BaseModel
{
    protected $searchBy = [
        'Nombre completo' => 'nombre',
        'Extensión de teléfono' => 'extension',
    ];

    public static function tableName()
    {
        return 'usuarios';
    }

    public static function rules()
    {
        return [
            [['nombre'], 'required', 'message' => 'Error: este campo es obligatorio.'],
            [['delegacion_id'], 'in', array_keys(Delegaciones::getAllDelegaciones(true)), 'message' => 'Error: Seleccione una opción válida.'],
        ];
    }

    protected static function labels()
    {
        return [
            'nombre' => 'Nombre completo',
            'delegacion_id' => 'Delegación',
            'extension' => 'Extensión de teléfono',
        ];
    }

    /**
     * Devuelve la delegación de este usuario.
     * @return Delegaciones|null Devuelve la delegación correspondiente o null
     * en caso de que no tenga ninguna asignada.
     */
    public function getDelegacion()
    {
        if ($this->delegacion_id) {
            $delegacion = new Delegaciones([
                'id' => $this->delegacion_id
            ]);
            if ($delegacion->readOne()) {
                return $delegacion;
            }
        }
        return null;
    }

    /**
     * Devuelve el nombre de la delegación asignada a este usuario.
     * @return string   Nombre de la delegación o cadena vacía en caso de que
     * no tenga ninguna asignada.
     */
    public function getNombreDelegacion()
    {
        $delegacion = $this->getDelegacion();
        return isset($delegacion->nombre) ? $delegacion->nombre : '';
    }

    /**
     * Devuelve los aparatos que este usuario está usando actualmente.
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
    public function getAparatosActuales()
    {
        $query = QueryBuilder::db($this->conn)
            ->select('*')
            ->from('aparatos')
            ->where(['usuario_id', $this->id])
            ->get();

        $data = $query->fetchAll();

        return $data;
    }

    /**
     * Devuelve los aparatos que este usuario ha usado anteriormente según los
     * datos de la tabla "aparatos_usuarios".
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
    public function getAparatosAnteriores()
    {
        $query = QueryBuilder::db($this->conn)
        ->select('a.*, au.usuario_id as anterior, au.created_at as hasta')
        ->from('aparatos_usuarios au')
        ->join('aparatos a', ['a.id', 'au.aparato_id'])
        ->where(['au.usuario_id', $this->id])
        ->orderBy('hasta DESC')
        ->get();

        $data = $query->fetchAll();

        return $data;
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
    public static function getAllUsuarios($withEmpty = false)
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
}
