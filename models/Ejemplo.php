<?php

namespace models;

use utilities\base\Database;
use utilities\base\BaseModel;

use utilities\query\QueryBuilder;

/**
 * Clase de ejemplo.
 * {@inheritdoc}
 */
class Ejemplo extends BaseModel
{
    protected $searchBy = [
        'Ejemplo' => 'ejemplo',
    ];

    public static function tableName()
    {
        return 'ejemplo';
    }

    public function rules()
    {
        return [
            [['ejemplo'], 'required', 'message' => 'Error: este campo es obligatorio.'],
            [['ejemplo'], 'string'],
            [['ejemplo'], 'max', 255, 'message' => 'Error: Excede el número máximo de caracteres (255).'],
        ];
    }

    protected static function labels()
    {
        return [
            'id' => 'ID',
            'ejemplo' => 'Ejemplo',
            'created_at' => 'Fecha de creación',
        ];
    }

    /**
     * Devuelve todos los ejemplos en un array del siguiente formato:
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
            ->select('id, ejemplo')
            ->from('ejemplo')
            ->get();

        $data = $query->fetchAll();

        $new = [];
        if ($withEmpty) {
            $new[''] = 'Ningún';
        }
        foreach ($data as $value) {
            $new[$value['id']] = $value['ejemplo'];
        }

        asort($new, SORT_NATURAL | SORT_FLAG_CASE);

        return $new;
    }
}
