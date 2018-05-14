<?php

namespace models;

use utilities\base\BaseModel;

/**
 *
 */
class AparatosUsuarios extends BaseModel
{
    protected $columnas = [];

    protected $searchBy = [];

    public static function tableName()
    {
        return 'aparatos_usuarios';
    }

    public static function rules()
    {
        return [

        ];
    }
}
