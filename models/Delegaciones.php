<?php

namespace models;

use utilities\base\Database;
use utilities\base\BaseModel;

use utilities\query\QueryBuilder;

/**
 *
 */
class Delegaciones extends BaseModel
{
    protected $searchBy = [
        'Nombre de la delegación' => 'nombre',
    ];

    public static function tableName()
    {
        return 'delegaciones';
    }

    public function rules()
    {
        return [
            [['nombre'], 'required', 'message' => 'Error: este campo es obligatorio.']
        ];
    }

    protected static function labels()
    {
        return [
            'nombre' => 'Nombre de la delegación',
        ];
    }

    protected function actionMessages()
    {
        return [
            'insert' => 'Ha registrado una nueva delegación: "' . $this->nombre . '".',
            'update' => 'Ha hecho modificaciones en la delegación "' . $this->nombre . '".',
            'delete' => 'Ha borrado la delegación "' . $this->nombre . '".',
        ];
    }

    /**
     * Devuelve todas las delegaciones en un array del siguiente formato:
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
            ->from('delegaciones')
            ->get();

        $data = $query->fetchAll();

        $new = [];
        if ($withEmpty) {
            $new[''] = 'Ningún';
        }
        foreach ($data as $value) {
            $new[$value['id']] = $value['nombre'];
        }

        return $new;
    }

    /**
     * Devuelve un array con los usuarios que están asociados a esta delegación.
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
    public function getUsuarios()
    {
        $query = QueryBuilder::db($this->conn)
            ->select('*')
            ->from('usuarios')
            ->where(['delegacion_id' => $this->id])
            ->get();

        $data = $query->fetchAll();

        return $data;
    }

    /**
     * Devuelve un array con los aparatos que esta delegación está usando
     * actualmente.
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
    public function getAparatos()
    {
        $query = QueryBuilder::db($this->conn)
            ->select('id, numero_serie')
            ->from('aparatos')
            ->where(['delegacion_id' => $this->id])
            ->get();

        $data = $query->fetchAll();

        return $data;
    }
}
