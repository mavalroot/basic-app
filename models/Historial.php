<?php

namespace models;

use utilities\base\BaseModel;

use utilities\helpers\html\Html;

/**
 *
 */
class Historial extends BaseModel
{
    protected $searchBy = [
        'Acción' => 'accion',
        'Tipo' => 'tipo',
    ];

    public static function tableName()
    {
        return 'historial';
    }

    public function rules()
    {
        $modelo = self::getModelByType();
        $modelo = isset($modelo) ? $modelo->getAll(true) : ['' => ''];
        return [
            [['tipo'], 'in', array_keys(static::getTypes()), 'message' => 'Error: Seleccione una opción válida.'],
            [['tipo'], 'required'],
            [['referencia'], 'in', array_keys($modelo)],
            [['created_by'], 'in', array_keys(Usuarios::getAll())],
            [['accion', 'tipo'], 'max', 255, 'message' => 'Error: Excede el número máximo de caracteres (255).'],
        ];
    }

    protected static function labels()
    {
        return [
            'id' => 'ID',
            'accion' => 'Acción',
            'tipo' => 'Tipo',
            'referencia' => 'Referencia',
            'created_at' => 'Fecha de creación',
            'created_by' => 'Usuario',
        ];
    }

    /**
     * Devuelve el modelo secundario que está relacionado con este modelo de
     * historial, relleno con sus datos correspondientes.
     * @return Ejemplo|Usuarios|null
     */
    public function getDatosAsociados()
    {
        $model = $this->getModelByType();
        if ($model->readOne()) {
            return $model;
        }
        return null;
    }

    /**
     * Devuelve un modelo vacío dependiendo del tipo del historial.
     * @return Ejemplo|Usuarios|null
     */
    public function getModelByType()
    {
        $id = isset($this->referencia) ? ['id' => $this->referencia] : false;
        $tipo = isset($this->tipo) ? $this->tipo : '';
        switch ($tipo) {
            case 'ejemplo':
                return new Ejemplo($id);
            case 'usuarios':
                return new Usuarios($id);
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
            'ejemplo' => 'Ejemplo',
            'usuarios' => 'Usuarios',
        ];
    }

    /**
     * Devuelve el rol que es responsable de esta acción.
     * @return Usuarios|null
     */
    public function getCreator()
    {
        $model = new Usuarios([
            'id' => $this->created_by,
        ]);
        if ($model->readOne()) {
            return $model;
        }
        return null;
    }

    /**
     * Devuelve el nombre del rol que es responsable de esta acción.
     * @return string
     */
    public function getCreatorName()
    {
        $model = $this->getCreator();
        if (isset($model)) {
            return $model->nombre;
        }
        return '';
    }

    /**
     * Devuelve la acción formateada como un link (o sin serlo en caso de que
     * se trate de un delete).
     * @return string
     */
    public function getAction()
    {
        $accion = '[' . $this->created_at . '] ' . $this->getCreatorName() . ': ' . $this->accion;
        if (isset($this->referencia)) {
            return Html::a([$accion, ['/' . $this->tipo . '/view.php', 'id' => $this->referencia]]);
        }
        return $accion;
    }
}
