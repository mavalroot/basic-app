<?php

namespace models;

use utilities\base\BaseModel;

/**
 *
 */
class AparatosUsuarios extends BaseModel
{
    protected $searchBy = [];

    public static function tableName()
    {
        return 'aparatos_usuarios';
    }

    public function rules()
    {
        return [
            [['aparato_id', 'usuario_id'], 'required', 'message' => 'Faltan el id del aparato o del usuario.']
        ];
    }
}
