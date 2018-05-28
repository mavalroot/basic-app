---------------------
------ EJEMPLO ------
---------------------

DROP TABLE IF EXISTS ejemplo CASCADE;

CREATE TABLE ejemplo (
      id            bigserial       PRIMARY KEY
    , ejemplo       varchar(255)    NOT NULL
    , created_at    timestamp(0)    DEFAULT localtimestamp
);


-----------------------------
--------- Usuarios ----------
-----------------------------
DROP TABLE IF EXISTS permisos CASCADE;

/**
 * Permisos que indicarán qué puede o no puede hacer un usuario.
 */
CREATE TABLE permisos (
      id        bigserial       PRIMARY KEY
    , permiso   varchar(255)    UNIQUE NOT NULL
);

DROP TABLE IF EXISTS usuarios CASCADE;

/**
 * Usuarios que pueden acceder a las tablas para hacer altas, bajas o
 * modificaciones según el permiso que posea.
 */
CREATE TABLE usuarios (
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

INSERT INTO usuarios (nombre, password_hash, permiso_id) VALUES
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
