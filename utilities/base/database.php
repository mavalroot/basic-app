<?php

namespace utilities\base;

use PDO;
use PDOException;

/**
 * @author María Valderrama Rodríguez <contact@mavalroot.es>
 * @copyright Copyright (c) 2018, María Valderrama Rodríguez
 *
 * Clase que nos permite conectarnos a una base de datos.
 */
class Database
{

    /**
     * Host de la base de datos
     * @var string
     */
    private $host = 'localhost';

    /**
     * Nombre de la base de datos
     * @var string
     */
    private $db_name = 'ejemplo';

    /**
     * Nombre del usuario que accederá a la base de datos.
     * @var string
     */
    private $username = 'ejemplo';

    /**
     * Contraseña de dicho usuario
     * @var string
     */
    private $password_hash = 'ejemplo';

    /**
     * Variable que se usará para la conexión.
     * @var null|PDO
     */
    public $conn;

    /**
     * Porporciona la conexión a la base de datos
     * @return PDO|PDOException
     */
    public function getConnection()
    {
        $this->conn = null;

        try {
            $this->conn = new PDO('pgsql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password_hash);
        } catch (PDOException $exception) {
            echo 'Error de conexión: ' . $exception->getMessage();
        }

        return $this->conn;
    }
}
