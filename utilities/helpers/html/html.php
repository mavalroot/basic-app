<?php

namespace utilities\helpers\html;

use DateTime;
use utilities\base\BaseModel;

/**
 * Proporciona un set de métodos estáticos que generan código HTML.
 */
class Html
{
    /**
     * Modelo usado como base para generar html.
     * @var array
     *
     * Es un array de tipo clave => valor, y contiene:
     *  [
     *      'model' => $model,  @var BaseModel  $model['model']
     *      'prop' => $prop,    @var string     $model['prop']
     *      'error' => $error,  @var string     $model['error']
     *      'valid' => $valid,  @var string     $model['valid']
     *      'table' => $table,  @var string     $model['table']
     *      'name' => $name,    @var string     $model['name']
     *      'value' => $value,  @var string     $model['value']
     *  ]
     */
    private static $model = false;

    /**
     * Label que se usará, si no se define se usará el nombre de la propiedad
     * del modelo, o si no usa modelo no se usará label.
     * @var string
     */
    private static $label = false;

    /**
     * Instancia de la clase Html que será devuelta para poder encadenar
     * métodos.
     * @var Html
     */
    private static $_instance = null;

    /**
     * Constructor de la clase, que será privado para que no se pueda
     * instanciar, ya que se trata de una clase estática para la cual usaremos
     * sus métodos únicamente.
     */
    private function __construct()
    {
    }

    /**
     * Devuelve la instancia de clase para poder encadenar métodos.
     * @return Html
     */
    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    /**
     * Establece el atributo $model de la clase.
     * @param BaseModel $model
     */
    private static function setModel($model)
    {
        self::$model = $model;
    }

    /**
     * Establece el atributo $label de la clase.
     * @param string $label
     */
    private static function setLabel($label)
    {
        static::$label = $label;
    }

    /**
     * Prepara los valores pasados como configuración.
     * @param  array $config Array de tipo clave valor que contiene la
     * configuración del resto de métodos.
     *
     * Son:
     * - message: determina si debajo de un input específico aparecerá un
     * mensaje explicativo.
     * - currency: determina si un input de tipo number recibe cantidad
     * monetaria, representada en formato 00,00.
     */
    private function prepareConfig(&$config)
    {
        $config['message'] = isset($config['message']) ? self::h($config['message']) : '';
        $config['currency'] = isset($config['currency']) ? $config['currency'] : false;
    }

    /**
     * Se establece un modelo y la propiedad de éste que se utilizará con los
     * métodos para generar html.
     * @param  BaseModel    $model
     * @param  string       $prop
     * @return Html
     */
    public static function form($model, $prop = false)
    {
        $prop = $prop ?: '';
        if ($model) {
            $error = $model->getErrors($prop);
            $valid = is_string($error) & $error != '' ? 'is-invalid' : '';
            $table = $model->tableName();
            $name = $table . '[' . $prop . ']';
            $value = isset($model->$prop) ? $model->$prop : '';
        }

        static::setModel([
            'model' => $model,
            'prop' => self::h($prop),
            'error' => self::h($error),
            'valid' => self::h($valid),
            'table' => self::h($table),
            'name' => self::h($name),
            'value' => self::h($value),
        ]);

        return static::getInstance();
    }

    /**
     * Se establece un label que se utilizará con los métodos para generar html.
     * @param  string $label
     * @return Html
     */
    public static function label($label)
    {
        static::setLabel(self::h($label));
        return static::getInstance();
    }

    /**
     * Genera un input de tipo text. En caso de tener cargado previamente un
     * modelo se usará éste como base, en caso contrario se mostrará un input
     * de tipo text corriente, con la cofiguración que se le haya pasado.
     * @param  array  $config Configuración para el input, es un array de
     * formato clave => valor.
     *  [
     *      'message' => 'mensaje que se mostrará bajo el input'
     *  ]
     */
    public static function textInput($config = [])
    {
        self::prepareConfig($config);
        if (static::$model) {
            $model = static::$model; ?>
            <div class="form-group row">
                <label for="<?= $model['name'] ?>" class="col-sm-2 col-form-label">
                    <?= static::$label ?: $model['prop'] ?>
                </label>
                <div class="col-sm-10">
                    <input type="text" id="<?= $model['name'] ?>" name="<?= $model['name'] ?>" value="<?= $model['value'] ?>" class="form-control <?= $model['valid'] ?>"  />

                    <small>
                        <?= $config['message'] ?>
                    </small>
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-circle"></i> <?= $model['error'] ?>
                    </div>
                </div>
            </div>
            <?php
        } else {
            ?>
            <div class="form-group">
                <label>
                    <input type="text" name="" value="" class="form-control">
                </label>
            </div>
            <?php
        }
    }

    /**
     * Genera un input de tipo number. En caso de tener cargado previamente un
     * modelo se usará éste como base, en caso contrario se mostrará un input
     * de tipo number corriente, con la cofiguración que se le haya pasado.
     * @param  array  $config Configuración para el input, es un array de
     * formato clave => valor.
     *  [
     *      'message' => 'mensaje que se mostrará bajo el input'
     *      'currency' => true/false
     *  ]
     */
    public static function numberInput($config = [])
    {
        self::prepareConfig($config);
        $currency = $config['currency'] == true ? 'step="any"' : '';
        if (static::$model) {
            $model = static::$model; ?>
            <div class="form-group row">
                <label for="<?= $model['name'] ?>" class="col-sm-2 col-form-label">
                    <?= static::$label ?: $model['prop'] ?>
                </label>
                <div class="col-sm-10">
                    <input type="number" id="<?= $model['name'] ?>" name="<?= $model['name'] ?>" value="<?= $model['value'] ?>" class="form-control <?= $model['valid'] ?>" <?= $currency ?>  />
                    <small>
                        <?= $config['message'] ?>
                    </small>
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-circle"></i> <?= $model['error'] ?>
                    </div>
                </div>
            </div>
            <?php
        } else {
            ?>
            <div class="form-group">
                <label>
                    <input type="number" name="" value="" class="form-control">
                </label>
            </div>
            <?php
        }
    }

    /**
     * Genera un input de tipo date. En caso de tener cargado previamente un
     * modelo se usará éste como base, en caso contrario se mostrará un input
     * de tipo date corriente, con la cofiguración que se le haya pasado.
     * @param  array  $config Configuración para el input, es un array de
     * formato clave => valor.
     *  [
     *      'message' => 'mensaje que se mostrará bajo el input'
     *  ]
     */
    public static function dateInput($config = [])
    {
        self::prepareConfig($config);
        if (static::$model) {
            $model = static::$model;
            $value = $model['value'] != '' ? date('Y-m-d', strtotime($model['value'])) : ''; ?>
            <div class="form-group row">
                <label for="<?= $model['name'] ?>" class="col-sm-2 col-form-label">
                    <?= static::$label ?: $model['prop'] ?>
                </label>
                <div class="col-sm-10">
                    <input type="date" id="<?= $model['name'] ?>" name="<?= $model['name'] ?>" value="<?= $value ?>" class="form-control <?= $model['valid'] ?>"  />
                    <small>
                        <?= $config['message'] ?>
                    </small>
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-circle"></i> <?= $model['error'] ?>
                    </div>
                </div>
            </div>
            <?php
        } else {
            ?>
            <div class="form-group">
                <label>
                    <input type="date" name="" value="" class="form-control" />
                </label>
            </div>
            <?php
        }
    }

    /**
     * Genera un input que es de solo lectura. En caso de tener cargado
     * previamente un modelo se usará éste como base, en caso contrario se
     * mostrará un input de solo lectura con la cofiguración que se le haya
     * pasado.
     * @param  array  $config Configuración para el input, es un array de
     * formato clave => valor.
     *  [
     *      'message' => 'mensaje que se mostrará bajo el input'
     *  ]
     */
    public static function readonlyInput($config = [])
    {
        self::prepareConfig($config);
        if (static::$model) {
            $model = static::$model; ?>
            <div class="form-group row">
                <label for="<?= $model['name'] ?>" class="col-sm-2 col-form-label">
                    <?= static::$label ?: $model['prop'] ?>
                </label>
                <div class="col-sm-10">
                    <input type="text" id="<?= $model['name'] ?>" value="<?= $model['value'] ?>" class="form-control <?= $model['valid'] ?> <?= $config['class'] ?>" disabled />
                    <small>
                        <?= $config['message'] ?>
                    </small>
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-circle"></i> <?= $model['error'] ?>
                    </div>
                </div>
            </div>
            <?php
        } else {
            ?>
            <div class="form-group">
                <label>
                    <input type="text" name="" value="" class="form-control <?= $config['class'] ?>">
                </label>
            </div>
            <?php
        }
    }

    /**
     * Devuelve una fila de tabla, pensado para los views.
     * @param  array  $config Configuración adicional.
     */
    public static function trTable($config = [])
    {
        self::prepareConfig($config);
        if (static::$model) {
            $model = static::$model;
            static::dateForm($model['value']); ?>
                <tr>
                    <th class="col-sm-2"><?= static::$label ?: $model['prop'] ?></th>
                    <td class="col-sm-10"><?= $model['value'] ?></td>
                </tr>
            <?php
        }
    }

    /**
     * Devuelve múltiples filas para una tabla, pensado para los view.
     * @param  bool|array $columns Columnas a ser mostradas como conjunto
     * clave => valor, donde clave es el label y valor el valor de la fila.
     * Si no se especifica nada se tomará las columnas especificadas en el
     * modelo.
     */
    public static function multiTrTable($columns = false)
    {
        if (static::$model) {
            $model = static::$model['model'];
            $columns = $columns ?: $model->getColumnas(); ?>
            <?php foreach ($columns as $label => $column): ?>
                <?php static::dateForm($model->$column) ?>
                <?php static::exists($model->$column) ?>
                <tr>
                    <th class="col-sm-2"><?= $label ? self::h($label) : self::h($column) ?></th>
                    <td class="col-sm-10"><?= self::h($model->$column) ?></td>
                </tr>
            <?php endforeach; ?>
            <?php
        }
    }

    /**
     * Genera inputs de tipo radio
     * @param  array    $options Opciones de los input, en formato clave => valor
     * Clave corresponde al label, y valor al valor del input.
     * @param  array    $config  Configuración extra.
     */
    public static function radioInput($options, $config = [])
    {
        self::prepareConfig($config);
        if (static::$model) {
            $model = static::$model;
            $set = isset($model['value']) ? $model['value'] : false; ?>
            <div class="form-group">
            <?php foreach ($options as $label => $value): ?>

                <div class="form-check">

                    <input type="radio" id=<?= self::h($value) ?> name="<?= $model['name'] ?>" value="<?= self::h($value) ?>" <?= $set ? 'disabled="true"' : '' ?> <?= $set == $value ? 'checked' : '' ?> class="form-check-input <?= $model['valid'] ?>">
                    <label for="<?= self::h($value) ?>" class="form-check-label"><?= self::h($label) ?></label>

                    <small>
                        <?= $config['message'] ?>
                    </small>
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-circle"></i> <?= $model['error'] ?>
                    </div>
                </div>

            <?php endforeach; ?>

            </div>
            <?php
        }
    }

    public static function selectOption($options, $config = [])
    {
        self::prepareConfig($config);
        if (static::$model) {
            $model = static::$model;
            $set = isset($model['value']) ? $model['value'] : false; ?>
            <div class="form-group row">
                <label for="<?= $model['name'] ?>" class="col-sm-2 col-form-label"><?= static::$label ?: $model['prop'] ?></label>
                <div class="col-sm-10">
                    <select id="<?= $model['name'] ?>" class="form-control <?= $model['valid'] ?>" name="<?= $model['name'] ?>" <?= $set ? 'disabled="true"' : '' ?>>
                        <option value="">-- Elegir --</option>
                        <?php foreach ($options as $key => $value): ?>
                            <option value="<?= $key ?>" <?= $set == $key ? 'selected' : '' ?>><?= $value ?></option>
                        <?php endforeach; ?>
                    </select>
                    <small>
                        <?= $config['message'] ?>
                    </small>
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-circle"></i> <?= $model['error'] ?>
                    </div>
                </div>
            </div>
            <?php
        }
    }

    public static function textarea($config = [])
    {
        self::prepareConfig($config);
        if (static::$model) {
            $model = static::$model; ?>
            <div class="form-group row">
                <label for="<?= $model['name'] ?>" class="col-sm-2 col-form-label">
                    <?= static::$label ?: $model['prop'] ?>
                </label>
                <div class="col-sm-10">
                    <textarea id="<?= $model['name'] ?>" name="<?= $model['name'] ?>" class="form-control <?= $model['valid'] ?>"  ><?= $model['value'] ?></textarea>

                    <small>
                        <?= $config['message'] ?>
                    </small>
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-circle"></i> <?= $model['error'] ?>
                    </div>
                </div>
            </div>
            <?php
        } else {
            ?>
            <div class="form-group">
                <label>
                    <input type="text" name="" value="" class="form-control">
                </label>
            </div>
            <?php
        }
    }

    /**
     * Comprueba que la variable introducida es una fecha, y si lo es la
     * formatea.
     * @param  string $date Fecha a comprobar.
     * @return string       Fecha formateada.
     */
    public static function dateForm(&$date)
    {
        if (DateTime::createFromFormat('Y-m-d G:i:s', $date) !== false) {
            $date = date('d/m/Y', strtotime($date));
        }
    }

    /**
     * Comprueba si una variable existe, devuelve cadena vacía si no.
     * @param  mixed $var Variable a comprobar.
     * @return mixed
     */
    public static function exists(&$var)
    {
        $var = isset($var) ? $var : '';
    }

    /**
     * Escapa los caracteres del string pasado como parámetro.
     * @param  string $var Variable a escapar.
     * @return string      Resultado escapado.
     */
    public static function h($var)
    {
        return htmlspecialchars($var);
    }

    /**
     * Muestra un alert.
     * @param  string $type Tipo de alert. Success, info, warning, danger...
     * @param  string $text Texto del alert.
     * @param  bool   $raw  Si es verdadero significa que el texto debe
     * mostrarse 'raw', sin escaparlo.
     */
    public static function alert($type, $text, $raw = false)
    {
        $text = $raw ? $text : self::h($text); ?>
        <div class='alert alert-<?= self::h($type) ?>'>
            <?= $text ?>
        </div>
        <?php
    }

    /**
     * Tipo:
     *
     * ['label', ['url', 'param1' => 'param', 'param2' => 'param'], ['class' => 'clases', 'attr' => ['atributo' => 'valor']]]
     * @param  array $array [description]
     * @param  bool $raw
     */
    public function a($array, $raw = false)
    {
        $label = $array[0];
        $class = isset($array[2]['class']) ? self::h($array[2]['class']) : '';
        $urlArr = $array[1];
        $url = '';
        $attrArr = isset($array[2]['attr']) ? $array[2]['attr'] : [];
        $attr = '';

        foreach ($attrArr as $key => $value) {
            $value = self::h($value);
            $key = self::h($key);
            $attr .= "{$key}=\"{$value}\" ";
        }

        if (!$raw) {
            $label = self::h($label);
        }

        $lastElement = end($urlArr);
        foreach ($urlArr as $key => $value) {
            $value = self::h($value);
            if (is_integer($key)) {
                $url .= $value;
                if ($value != $lastElement) {
                    $url .= '?';
                }
            } else {
                $key = self::h($key);
                $url .= "{$key}={$value}";
                if ($value != $lastElement) {
                    $url .= '&';
                }
            }
        }

        // return "<a href=\"{$url}\" class=\"{$class}\" {$attr}>{$label}</a>";
        ?>
            <a href="<?= $url ?>" class="<?= $class ?>" <?= $attr ?>>
                <?= $label ?>
            </a>
        <?php
    }
}
