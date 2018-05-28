<?php

namespace utilities\helpers\html;

use DateTime;
use utilities\base\BaseModel;

/**
 * @author María Valderrama Rodríguez <contact@mavalroot.es>
 * @copyright Copyright (c) 2018, María Valderrama Rodríguez
 *
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
        $config['locked'] = isset($config['locked']) ? $config['locked'] : true;
        $config['select'] = isset($config['select']) ? $config['select'] : false;
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
            $model = static::$model;
            extract($model) ?>
            <div class="form-group row">
                <label for="<?= $name ?>" class="col-sm-2 col-form-label">
                    <?= static::$label ?: $model->getLabel($prop) ?>
                </label>
                <div class="col-sm-10">
                    <input type="text" id="<?= $name ?>" name="<?= $name ?>" value="<?= $value ?>" class="form-contusuario <?= $valid ?>"  />

                    <small>
                        <?= $config['message'] ?>
                    </small>
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-circle"></i> <?= $error ?>
                    </div>
                </div>
            </div>
            <?php
        } else {
            ?>
            <div class="form-group">
                <label>
                    <input type="text" name="" value="" class="form-contusuario">
                </label>
            </div>
            <?php
        }
        static::setLabel(false);
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
    public static function passwordInput($config = [])
    {
        self::prepareConfig($config);
        if (static::$model) {
            $model = static::$model;
            extract($model) ?>
            <div class="form-group row">
                <label for="<?= $name ?>" class="col-sm-2 col-form-label">
                    <?= static::$label ?: $model->getLabel($prop) ?>
                </label>
                <div class="col-sm-10">
                    <input type="password" id="<?= $name ?>" name="<?= $name ?>" class="form-contusuario <?= $valid ?>"  />

                    <small>
                        <?= $config['message'] ?>
                    </small>
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-circle"></i> <?= $error ?>
                    </div>
                </div>
            </div>
            <?php
        } else {
            ?>
            <div class="form-group">
                <label>
                    <input type="password" name="" value="" class="form-contusuario">
                </label>
            </div>
            <?php
        }
        static::setLabel(false);
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
            $model = static::$model;
            extract($model) ?>
            <div class="form-group row">
                <label for="<?= $name ?>" class="col-sm-2 col-form-label">
                    <?= static::$label ?: $model->getLabel($prop) ?>
                </label>
                <div class="col-sm-10">
                    <input type="number" id="<?= $name ?>" name="<?= $name ?>" value="<?= $value ?>" class="form-contusuario <?= $valid ?>" <?= $currency ?>  />
                    <small>
                        <?= $config['message'] ?>
                    </small>
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-circle"></i> <?= $error ?>
                    </div>
                </div>
            </div>
            <?php
        } else {
            ?>
            <div class="form-group">
                <label>
                    <input type="number" name="" value="" class="form-contusuario">
                </label>
            </div>
            <?php
        }
        static::setLabel(false);
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
            extract($model);
            $value = $value != '' ? date('Y-m-d', strtotime($value)) : ''; ?>
            <div class="form-group row">
                <label for="<?= $name ?>" class="col-sm-2 col-form-label">
                    <?= static::$label ?: $model->getLabel($prop) ?>
                </label>
                <div class="col-sm-10">
                    <input type="date" id="<?= $name ?>" name="<?= $name ?>" value="<?= $value ?>" class="form-contusuario <?= $valid ?>"  />
                    <small>
                        <?= $config['message'] ?>
                    </small>
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-circle"></i> <?= $error ?>
                    </div>
                </div>
            </div>
            <?php
        } else {
            ?>
            <div class="form-group">
                <label>
                    <input type="date" name="" value="" class="form-contusuario" />
                </label>
            </div>
            <?php
        }
        static::setLabel(false);
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
            $model = static::$model;
            extract($model); ?>
            <div class="form-group row">
                <label for="<?= $name ?>" class="col-sm-2 col-form-label">
                    <?= static::$label ?: $model->getLabel($prop) ?>
                </label>
                <div class="col-sm-10">
                    <input type="text" id="<?= $name ?>" value="<?= $value ?>" class="form-contusuario <?= $valid ?> <?= $config['class'] ?>" disabled />
                    <small>
                        <?= $config['message'] ?>
                    </small>
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-circle"></i> <?= $error ?>
                    </div>
                </div>
            </div>
            <?php
        } else {
            ?>
            <div class="form-group">
                <label>
                    <input type="text" name="" value="" class="form-contusuario <?= $config['class'] ?>">
                </label>
            </div>
            <?php
        }
        static::setLabel(false);
    }

    /**
     * Devuelve una fila de tabla, pensado para los views.
     * @param  array  $config Configuración adicional.
     */
    public static function trTable($config = [])
    {
        $prop = false;
        $value = false;
        $label = false;
        $model = false;
        if (static::$model) {
            $modelo = static::$model;
            extract($modelo, EXTR_IF_EXISTS);
        }
        extract($config, EXTR_IF_EXISTS);
        if (is_string($value)) {
            static::dateForm($value);
        } else {
            $value = call_user_func($value, $model);
        }
        if (!$label) {
            $label = static::$label ?: $model->getLabel($prop);
        }
        ?>
            <tr>
                <th class="col-sm-2"><?= $label ?></th>
                <td class="col-sm-10"><?= isset($value) ? nl2br($value) : '' ?></td>
            </tr>
        <?php
        static::setLabel(false);
    }

    /**
     * Devuelve múltiples filas para una tabla, pensado para los view.
     * @param  array $config Configuración adicional opcional.
     */
    public static function multiTrTable($config = [])
    {
        $columns = false;
        $exclude = [];
        extract($config, EXTR_IF_EXISTS);
        if (static::$model) {
            $model = static::$model['model'];
            $columns = $columns ?: $model->getAllColumns(); ?>
            <?php foreach ($columns as $column): ?>
                <?php if (in_array($column, $exclude)): ?>
                    <?php continue; ?>
                <?php endif; ?>
                <?php if ($model->$column === false): ?>
                    <?php $model->$column = 'No' ?>
                <?php elseif ($model->$column === true): ?>
                    <?php $model->$column = 'Sí' ?>
                <?php endif; ?>
                <?php static::dateForm($model->$column) ?>
                <?php static::exists($model->$column) ?>
                <tr>
                    <th class="col-sm-2"><?= $model->getLabel($column) ?: $column ?></th>
                    <td class="col-sm-10"><?= nl2br(self::h($model->$column)) ?></td>
                </tr>
            <?php endforeach;
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
            extract($model);
            $set = isset($value) ? $value : false; ?>
            <div class="form-group">
            <?php foreach ($options as $label => $valor): ?>

                <div class="form-check">

                    <input type="radio" id=<?= self::h($valor) ?> name="<?= $name ?>" value="<?= self::h($valor) ?>" <?= $set && $config['locked'] ? 'disabled="true"' : '' ?> <?= $set == $valor ? 'checked' : '' ?> class="form-check-input <?= $valid ?>">
                    <label for="<?= self::h($valor) ?>" class="form-check-label"><?= self::h($label) ?></label>

                    <small>
                        <?= $config['message'] ?>
                    </small>
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-circle"></i> <?= $error ?>
                    </div>
                </div>

            <?php endforeach; ?>

            </div>
            <?php
            static::setLabel(false);
        }
    }

    /**
     * Genera un select con sus opciones.
     * @param  array $options options para el select, deben seguir el formato:
     * ['valor' => 'label', 'valor' => 'label'].
     * Por ejemplo:
     * ['0' => 'No', '1' => 'Sí'].
     * @param  array  $config  Configuracióna adicional opcional. Incluye:
     * 'locked' => True o false. Indica si una vez relleno dicho select se
     * queda "disabled".
     * 'select' => True o false. Indica si se añade una opción "--Elegir--".
     */
    public static function selectOption($options, $config = [])
    {
        self::prepareConfig($config);
        if (static::$model) {
            $model = static::$model;
            extract($model);
            $set = isset($value) ? $value : false; ?>
            <div class="form-group row">
                <label for="<?= $name ?>" class="col-sm-2 col-form-label"><?= static::$label ?: $model->getLabel($prop) ?></label>
                <div class="col-sm-10">
                    <select id="<?= $name ?>" class="form-contusuario <?= $valid ?>" name="<?= $name ?>" <?= $set && $config['locked'] ? 'disabled="true"' : '' ?>>
                        <?php if ($config['select']): ?>
                            <option value="">-- Elegir --</option>
                        <?php endif; ?>
                        <?php foreach ($options as $key => $valor): ?>
                            <option value="<?= $key ?>" <?= $set == $key ? 'selected' : '' ?>><?= $valor ?></option>
                        <?php endforeach; ?>
                    </select>
                    <small>
                        <?= $config['message'] ?>
                    </small>
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-circle"></i> <?= $error ?>
                    </div>
                </div>
            </div>
            <?php
            static::setLabel(false);
        }
    }

    /**
     * Genera un textarea.
     * @param  array  $config Configuración adicional opcional.
     */
    public static function textarea($config = [])
    {
        self::prepareConfig($config);
        if (static::$model) {
            $model = static::$model;
            extract($model); ?>
            <div class="form-group row">
                <label for="<?= $name ?>" class="col-sm-2 col-form-label">
                    <?= static::$label ?: $model->getLabel($prop) ?>
                </label>
                <div class="col-sm-10">
                    <textarea id="<?= $name ?>" name="<?= $name ?>" class="form-contusuario <?= $valid ?>"  ><?= preg_replace('/<br\\s*?\/??>/i', '', $value) ?></textarea>

                    <small>
                        <?= $config['message'] ?>
                    </small>
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-circle"></i> <?= $error ?>
                    </div>
                </div>
            </div>
            <?php
        } else {
            ?>
            <div class="form-group">
                <label>
                    <input type="text" name="" value="" class="form-contusuario">
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
            if (date('G:i:s', strtotime($date)) == '0:00:00') {
                $date = date('d/m/Y', strtotime($date));
            } else {
                $date = date('d/m/Y  G:i:s', strtotime($date));
            }
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
