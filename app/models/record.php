<?php
class RecordModel {
    private ?PDO $connection = null; 

    public function __construct(PDO $connection){
        $this->connection = $connection;
    }

    /* 
    Este método valida la existencia de el correo
    en caso de que no exista ser inserta este
    y ser retorna el ID del nuevo o existente registro
    */
    private function validate_email($email) {
        //búsqueda de correo electrónico registro
        try {
            $sql = 'SELECT id FROM correo_registro WHERE correo = ?';
            $prepare = $this->connection->prepare($sql);
            $prepare->execute([$email]);
            $idEmail = $prepare->fetch(PDO::FETCH_OBJ);
            if ($idEmail) {
                return $idEmail->id;
            } else {
                try {
                    //insertar nuevo correo registro y retornar id
                    $sql = 'INSERT INTO correo_registro (correo) VALUES (?)';
                    $prepare = $this->connection->prepare($sql);
                    $prepare->execute([$email]);
                    //validar si se insertaron registros
                    if ($prepare->rowCount()) {
                        return $this->connection->lastInsertId();
                    } else {
                        // No se insertaron filas
                        return false;
                    }
                } catch (PDOException $e) {
                    error_log($e->getMessage());
                    return false;
                }
            }
        } catch (PDOException $e) {
            //almacenar log_error
            error_log($e->getMessage());
        }
    }

    /* 
    Este método valida si existe un nombre de sitio web
    en caso de que no se inserta este y ser retorna
    el ID del nuevo o existente registro
    */
    private function validate_website_name($website) {
        try {
            $sql = 'SELECT id FROM portal_registro WHERE nombre = ?';
            $prepare = $this->connection->prepare($sql);
            $prepare->execute([$website]);
            $idWebsite = $prepare->fetch(PDO::FETCH_OBJ);
            //validar si encontró datos
            if ($idWebsite) {
                return $idWebsite->id;
            } else {
                //insertar nuevo portal registro y retornar id
                try {
                    $sql = 'INSERT INTO portal_registro (nombre) VALUES (?)';
                    $prepare = $this->connection->prepare($sql);
                    $prepare->execute([$website]);
                    //validare inserción de registro
                    if ($prepare->rowCount()) {
                        return $this->connection->lastInsertId();
                    } else {
                        // No se insertaron filas
                        return false;
                    }
                } catch (PDOException $e) {
                    error_log($e->getMessage());
                    return false;
                }
            }
        } catch (PDOException $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    /* 
    Este método valida si existe el teléfono en caso
    de que no exista se inserta este y se retoña el ID
    del nuevo o existente registro
    */
    private function validate_tel($tel) {
        //búsqueda teléfono registro
        try {
            $sql = 'SELECT id FROM telefono_registro WHERE telefono = ?';
            $prepare = $this->connection->prepare($sql);
            $prepare->execute([$tel]);
            $idTel = $prepare->fetch(PDO::FETCH_OBJ);
            //validar si se retornan datos
            if ($idTel) {
                return $idTel->id;
            } else {
                try {
                    // insertar nuevo teléfono registro
                    $sql = 'INSERT INTO telefono_registro (telefono) VALUES (?)';
                    $prepare = $this->connection->prepare($sql);
                    $prepare->execute([$tel]);
                    //validar inserción de registro
                    if ($prepare->rowCount()) {
                        //retorna el id del teléfono
                        return $this->connection->lastInsertId();
                    } else {
                        // no se insertaron filas
                        return false;
                    }
                } catch (PDOException $e) {
                    // Guardar log
                    error_log($e->getMessage());
                }
            }
        } catch (PDOException $e) {
            error_log($e->getMessage());
        }
    }

    /* 
    Este valida si existe un nombre de usuario
    en caso de que no exista insertar este y retornar
    el ID del nuevo registro o del existente
    */
    private function validate_username($username) {
        //búsqueda nombre_registro
        try {
            $sql = 'SELECT id FROM nombre_registro WHERE nombre = ?';
            $prepare = $this->connection->prepare($sql);
            $prepare->execute([$username]);
            $idUsername = $prepare->fetch(PDO::FETCH_OBJ);
            //validar si se encuentran registros
            if ($idUsername) {
                return $idUsername->id;
            } else {
                //insertar nombre_registro 'nombre usuario'
                try {
                    $sql = 'INSERT INTO nombre_registro (nombre) VALUES (?)';
                    $prepare = $this->connection->prepare($sql);
                    $prepare->execute([$username]);
                    //validar si se inserto el registro
                    if ($prepare->rowCount()) {
                        //retoñar id de inserción
                        return $this->connection->lastInsertId();
                    } else {
                        // no se insertaron datos
                        return false;
                    }
                } catch (PDOException $e) {
                    error_log($e->getMessage());
                    return false;
                }
            }
        } catch (PDOException $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    //insertar nuevo registro
    public function save_record(array $data) {
        /*
        $data, tendera la configuración inicial
        de los datos que fueron enviados como array
        desde la función 'save_record > controller'
        y sera este el array que se pasara como argumento
        a la sentencia preparada
        */
        $data = [
            'id_usuario'           => $data['idUser'],
            'id_portal_registro'   => $data['website'],
            'id_correo_registro'   => $data['email'],
            'id_nombre_registro'   => $data['username'],
            'id_telefono_registro' => $data['tel'],
            'contrasenia'          => $data['password'],
            'clave_recuperacion'   => $data['recoveryKey']
        ];

        /* 
        Las sentencias de control siguientes se encargaran
        de realizar una validación de existencia y en caso de no
        existir realizara una inserción, estos dos retornaran el id de dato
        en caso de existir y del nuevo registro insertado.
        */
        if ($data['id_portal_registro'] !== '') {
            $idWebsite = $this->validate_website_name($data['id_portal_registro']);
            $data['id_portal_registro'] = $idWebsite;
        }

        if ($data['id_correo_registro'] !== '') {
            $idEmail = $this->validate_email($data['id_correo_registro']);
            $data['id_correo_registro'] = $idEmail;
        }

        if ($data['id_nombre_registro'] !== '') {
            $idUsername = $this->validate_username($data['id_nombre_registro']);
            $data['id_nombre_registro'] = $idUsername;
        }

        if ($data['id_telefono_registro'] !== '') {
            $idTel = $this->validate_tel($data['id_telefono_registro']);
            $data['id_telefono_registro'] = $idTel;
        }

        /*
        Construcción final del array $data.
        El bucle tiene la función de eliminar los valores que se encuentran 
        representados como cadenas vacías ('') dentro del array $data. Esto 
        se realiza con el objetivo de preparar la inserción de datos en el 
        array de manera adecuada, evitando la presencia de valores no existentes.
        */
        foreach ($data as $key => $value) {
            if ($value === ''){
                unset($data[$key]);
            }
        }

        /*
        Construcción inicial de sentencia SQL, la construcción 
        dinámica de campos y de marcadores, para la posterior 
        elaboración de consulta preparada sql
        */
        $field = '';
        $marker = '';
        foreach ($data as $key => $value) {
            $field .= ($field ? ', ' : '') . $key;
            $marker .= ($marker ? ', ' : '') . ":$key";
        }

        // Construcción final de sentencia SQL
        $sql = "INSERT INTO dato_registro ($field) VALUES ($marker)";

        // Inserción de datos
        try {
            $prepare = $this->connection->prepare($sql);
            $prepare->execute($data);
            return $prepare->rowCount() > 0;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}