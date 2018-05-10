<?php

namespace utilities\base;

use PDO;
use PDOException;

/**
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
    private $db_name = 'inventario';

    /**
     * Nombre del usuario que accederá a la base de datos.
     * @var string
     */
    private $username = 'inventario';

    /**
     * Contraseña de dicho rol
     * @var string
     */
    private $password = 'inventario';

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
            $this->conn = new PDO('pgsql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
        } catch (PDOException $exception) {
            echo 'Error de conexión: ' . $exception->getMessage();
        }

        return $this->conn;
    }
}
