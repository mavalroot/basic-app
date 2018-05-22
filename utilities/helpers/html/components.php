<?php

namespace utilities\helpers\html;

use models\Permisos;

use utilities\helpers\validation\Checker;

/**
 * Nos proporciona una serie de componentes html.
 */
class Components
{
    /**
     * Constructor privado para que no se creen instancias de la clase.
     */
    private function __construct()
    {
    }

    /**
     * Muestra el header.
     * @param   string  $pageTitle      Title y page-header.
     * @param   array   $breadcrumps    Migas de pan. Debe seguir el siguiente
     * formato:
     *
     * $breadcrumps = [
     *     'Nommbre' => '../url/url.php',
     *     'Nombre' => 'url.php',
     *     'Actual' => ''
     * ];
     *
     * @param   bool    $navbar         Indica si quiere o no que se muestre
     * la barra de navegación. Por defecto sí se mostrará.
     *
     * @param   bool    $check          Indica si quiere o no que se haga la
     * comprobación de que el rol esté conectado. Pensado para el login.
     */
    public static function header($pageTitle, $breadcrumps = [], $navbar = true, $check = true)
    {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1">

            <title><?= $pageTitle ?></title>

            <link rel="stylesheet" href="../libs/css/bootstrap.min.css" />
            <link rel="stylesheet" href="../libs/css/fontawesome/css/fontawesome-all.css">
            <link rel="stylesheet" href="../libs/css/custom.css" />
            <script src="../libs/js/jquery-3.3.1.min.js"></script>
            <script src="../libs/js/bootstrap.min.js"></script>
            <script src="../libs/js/jspdf.min.js"></script>
            <script src="../libs/js/jspdf.plugin.autotable.js" charset="utf-8"></script>
            <script src="../libs/js/delete.js"></script>
            <script src="../libs/js/guardarpdf.js"></script>
            <script src="../libs/js/guardarqr.js"></script>
            <script src="../libs/js/extra-actions.js"></script>
            <script type="text/javascript" src="../libs/js/jquery.qrcode.min.js"></script>
        </head>
        <body>
            <div id="wrap">
            <?php if ($navbar): ?>
                <?= self::navbar() ?>
            <?php endif; ?>
            <?php if ($check): ?>
                <?php Checker::checkLogin() ?>
            <?php endif; ?>
                <div class="container-fluid">
                    <?php if ($breadcrumps): ?>
                        <?= self::breadcrumps($breadcrumps); ?>
                    <?php endif; ?>

                    <h1 class='page-header'>
                        <h1><?= $pageTitle ?></h1>
                    </h1>

        <?php
    }

    /**
     * Muestra el footer.
     * @param bool $bool
     */
    public static function footer($bool = true)
    {
        ?>
            </div>
        </div>
        <?php if ($bool): ?>
            <footer class="page-footer font-small bg-dark text-white mt-5">
                <div class="footer-copyright py-3 text-center">
                    © <?= date("Y"); ?> Ayto. de Chipiona
                </div>
            </footer>
        <?php endif; ?>
        </body>
        </html>
        <?php
    }

    /**
     * Muestra la paginación.
     * @param  int      $totalRows  Total de filas de la tabla.
     * @param  int      $page       Página actual.
     * @param  int      $pagLimit   Filas que se muestran por páginas.
     * @param  int      $pagOffset  Offset.
     * @param  string   $pageUrl    Url para el paginador.
     */
    public static function pagination($totalRows, $page, $pagLimit, $pagOffset, $pageUrl)
    {
        ?>
        <nav aria-label="Paginacion">
        <ul class="pagination">
        <?php
        // Botón para la primera página
        if ($page > 1) {
            ?>
            <li class="page-item">
                <a class="page-link" href='<?= $pageUrl ?>' title='Ir a la primera página.'>Primera</a>
            </li>
            <?php
        }
        // Cuenta todos los registros de la base de datos para calcular el número de páginas.
        $totalPages = ceil($totalRows / $pagLimit);

        $range = 2;
        $initialNum = $page - $range;
        $conditionLimitNum = ($page + $range);

        if ($initialNum > 1) {
            ?>
            <li class="page-item disabled">
                <a class="page-link" href='#'>
                    ...
                </a>
            </li>
            <?php
        }

        for ($x = $initialNum; $x <= $conditionLimitNum; $x++) {
            if (($x > 0) && ($x <= $totalPages)) {
                // Página actual
                if ($x == $page) {
                    ?>
                    <li class='page-item active'>
                        <a class="page-link" href=#>
                            <?= $x ?>
                            <span class="sr-only">(actual)</span>
                        </a>
                    </li>
                    <?php
                } else {
                    ?>
                    <li class="page-item">
                        <a class="page-link" href='<?= $pageUrl ?>page=<?= $x ?>'>
                            <?= $x ?>
                        </a>
                    </li>
                    <?php
                }
            }
        }
        if ($conditionLimitNum < $totalPages) {
            ?>
            <li class="page-item disabled">
                <a class="page-link" href='#'>
                    ...
                </a>
            </li>
            <?php
        }
        // Botón para la última página
        if ($page < $totalPages) {
            ?>
            <li class="page-item">
                <a class="page-link" href='<?= $pageUrl ?>page=<?= $totalPages?>' title='Ir a la última página'>
                    Última
                </a>
            </li>
            <?php
        } ?>
        </ul>
        </nav>
        <?php
    }

    /**
     * Muestra las migas de pan.
     * @param  array $array Array clave => valor que contiene nombre y url
     * para formar las migas de pan.
     */
    public static function breadcrumps($array)
    {
        ?>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <?php foreach ($array as $label => $url): ?>
                <?php if ($url === ''): ?>
                    <li class="breadcrumb-item active" aria-current="page"><?= $label ?></li>
                <?php else: ?>
                    <li class="breadcrumb-item"><a href="<?= $url ?>"><?= $label ?></a></li>
                <?php endif; ?>
            <?php endforeach; ?>
          </ol>
        </nav>
        <?php
    }

    /**
     * Muestra la navbar.
     */
    public static function navbar()
    {
        ?>
        <nav class="navbar navbar-expand-lg navbar navbar-light border-bottom border-warning fixed-top" style="background-color: #f2f2f2">
          <a class="navbar-brand" href="../site/index.php"><img src="../libs/images/ayto.png" height="50" class="d-inline-block align-middle">Ayto. de Chipiona</a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li><a href="../aparatos/index.php" class="nav-link">Aparatos</a></li>
                <li><a href="../usuarios/index.php" class="nav-link">Usuarios</a></li>
                <li><a href="../delegaciones/index.php" class="nav-link">Delegaciones</a></li>
                <?php if (Checker::checkPermission(Permisos::ADMIN)): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      Administración
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                      <a class="dropdown-item" href="../historial/index.php">Historial</a>
                      <a class="dropdown-item" href="../roles/index.php">Roles</a>
                    </div>
                </li>
                <?php endif; ?>
                <li><a href="../site/permisos.php" class="nav-link">Tabla de permisos</a></li>
            </ul>
            <?php if (isset($_SESSION['nombre'])): ?>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="../roles/logout.php" class="nav-link">Logout ( <?= $_SESSION['nombre'] ?> )</a></li>
            </ul>
            <?php else: ?>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="../roles/login.php" class="nav-link">Login</a></li>
            </ul>
            <?php endif ?>
          </div>
        </nav>
        <?php
    }

    /**
     * Muestra la barra de búsqueda
     * @param  array  $options     Opciones de búsqueda.
     * @param  string $searchTerm Término de búsqueda.
     */
    public static function search($options, $searchTerm = null)
    {
        $options = is_string($options) ? [$options] : $options; ?>
            <form role='search' action='index.php'>
                <div class='input-group'>
                    <?php $search_value=isset($searchTerm) ? "value='" . $searchTerm ."'" : ""; ?>
                    <input type='text' class='form-control' placeholder='Introduzca un término de búsqueda.' name='search' id='srch-term' required <?= $search_value ?> />
                    <div class='input-group-append'>
                        <select class="btn btn-outline-secondary" name="by">
                            <?php foreach ($options as $label => $value): ?>
                                <?php $selected = $value == $_GET['by'] ? 'selected' : '' ?>
                                <option value="<?= $value ?>" <?= $selected ?>><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button class='btn btn-outline-secondary' type='submit'><i class="fas fa-search"></i></button>
                        <a class='btn btn-outline-secondary' href="index.php" title="Limpiar búsqueda"><i class="fas fa-trash-alt"></i></a>
                    </div>
                </div>
            </form>
        <?php
    }
}
