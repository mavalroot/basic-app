--------------------------
------- INVENTARIO -------
--------------------------
DROP TABLE IF EXISTS delegaciones CASCADE;

/**
 * Delegaciones.
 */
CREATE TABLE delegaciones (
      id            bigserial       PRIMARY KEY
    , nombre        varchar(255)    NOT NULL
    , created_at    timestamp(0)    DEFAULT localtimestamp
);

DROP TABLE IF EXISTS usuarios CASCADE;

/**
 * roles que utilizan o han utilizado un aparato.
 */
CREATE TABLE usuarios (
      id            bigserial       PRIMARY KEY
    , nombre        varchar(255)
    , delegacion_id bigint          REFERENCES delegaciones (id)
    , extension     varchar(255)
    , created_at    timestamp(0)    DEFAULT localtimestamp
);

DROP TABLE IF EXISTS aparatos CASCADE;

/**
* Aparatos que son utilizados por roles.
*/
CREATE TABLE aparatos (
      id            bigserial       PRIMARY KEY
    , usuario_id    bigint          REFERENCES roles (id)
    , delegacion_id bigint          REFERENCES delegaciones (id)
    , marca         varchar(255)
    , modelo        varchar(255)
    , num_serie     varchar(255)
    , fecha_compra  timestamp(0)
    , proveedor     varchar(255)
    , tipo          varchar(255)
    , observaciones text
    , created_at    timestamp(0)    DEFAULT localtimestamp
);

DROP TABLE IF EXISTS aparatos_usuarios CASCADE;

/**
 * Recoge los roles que han utilizado ese aparato, pero que actualmente ya
 * no lo hacen.
 */
CREATE TABLE aparatos_usuarios (
      id            bigserial       PRIMARY KEY
    , usuario_id    bigint          NOT NULL REFERENCES roles (id)
    , aparato_id    bigint          NOT NULL REFERENCES aparatos (id)
                                    ON DELETE CASCADE ON UPDATE CASCADE
    , created_at    timestamp(0)    DEFAULT localtimestamp
);

DROP TABLE IF EXISTS ordenadores CASCADE;

/**
 * Aparatos -> Ordenadores.
 */
CREATE TABLE ordenadores (
      aparato_id    bigint          PRIMARY KEY REFERENCES aparatos (id)
                                    ON DELETE CASCADE ON UPDATE CASCADE
    , micro         varchar(255)
    , memoria       varchar(255)
    , disco_duro    varchar(255)
    , tipo_disco    varchar(255)
    , ip            varchar(255)
    , sist_op       varchar(255)
);

DROP TABLE IF EXISTS impresoras CASCADE;

/**
 * Aparatos -> Impresoras.
 */
CREATE TABLE impresoras (
      aparato_id    bigint          PRIMARY KEY REFERENCES aparatos (id)
                                    ON DELETE CASCADE ON UPDATE CASCADE
    , cartucho      varchar(255)
    , magenta       varchar(255)
    , cian          varchar(255)
    , amarillo      varchar(255)
    , negro         varchar(255)
    , red           boolean
);

DROP TABLE IF EXISTS monitores CASCADE;

/**
 * Aparatos -> Monitores.
 */
CREATE TABLE monitores (
      aparato_id    bigint          PRIMARY KEY REFERENCES aparatos (id)
                                    ON DELETE CASCADE ON UPDATE CASCADE
    , pulgadas      varchar(255)
);

DROP TABLE IF EXISTS perifericos CASCADE;

/**
 * Aparatos -> Periféricos.
 */
CREATE TABLE perifericos (
      aparato_id    bigint  PRIMARY KEY REFERENCES aparatos (id)
                            ON DELETE CASCADE ON UPDATE CASCADE
    , descripcion   text
);

DROP TABLE IF EXISTS electronica_red CASCADE;

/**
 * Aparatos -> Electrónica de red.
 */
CREATE TABLE electronica_red (
      aparato_id    bigint          PRIMARY KEY REFERENCES aparatos (id)
                                    ON DELETE CASCADE ON UPDATE CASCADE
    , ubicacion     varchar(255)
    , tipo          varchar(255)
    , descripcion   text
);

--------------------------
------- HISTORIAL --------
--------------------------
DROP TABLE IF EXISTS historial CASCADE;

/**
 * Historial que guarda información sobre altas, bajas y modificaciones.
 */
CREATE TABLE historial (
      id            bigserial       PRIMARY KEY
    , accion        varchar(255)    NOT NULL
    , tipo          varchar(255)    NOT NULL
    , referencia    bigint
    , created_at    timestamp(0)    DEFAULT localtimestamp
    , created_by    bigint          NOT NULL REFERENCES roles (id)
);

--------------------------
--------- roles ----------
--------------------------
DROP TABLE IF EXISTS permisos CASCADE;

/**
 * Permisos que indicarán qué puede o no puede hacer un rol.
 */
CREATE TABLE permisos (
      id        bigserial       PRIMARY KEY
    , permiso   varchar(255)    UNIQUE NOT NULL
);

DROP TABLE IF EXISTS roles CASCADE;

/**
 * roles que pueden acceder a las tablas para hacer altas, bajas o
 * modificaciones según el permiso que posea.
 */
CREATE TABLE roles (
      id            bigserial       PRIMARY KEY
    , nombre        varchar(25)     UNIQUE NOT NULL
    , password_hash varchar(255)    NOT NULL
    , permiso_id    bigint          NOT NULL REFERENCES permisos (id)
    , last_con      timestamp(0)    DEFAULT localtimestamp
    , created_at    timestamp(0)    DEFAULT localtimestamp
);

--------------------
----- INSERTS ------
--------------------
INSERT INTO permisos (permiso) VALUES
      ('lector')
    , ('editor')
    , ('normal')
    , ('admin')
    , ('qronly')
;

INSERT INTO roles (nombre, password_hash, permiso_id) VALUES
      (
          'administrador'
        , crypt('administrador', gen_salt('md5'))
        , 4
    )
    , (
          'usuario'
        , crypt('usuario', gen_salt('md5'))
        , 3
    )
    , (
          'lector'
        , crypt('lector', gen_salt('md5'))
        , 1
    )
    , (
          'editor'
        , crypt('editor', gen_salt('md5'))
        , 2
    )
    , (
          'qronly'
        , crypt('qronly', gen_salt('md5'))
        , 5
    )
;
