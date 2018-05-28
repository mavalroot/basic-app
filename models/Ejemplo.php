<?php

namespace models;

use utilities\base\BaseModel;

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
}
