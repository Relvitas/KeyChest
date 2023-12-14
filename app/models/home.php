<?php
class HomeModel {
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
                return $idEmail;
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
                return $idWebsite;
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
                return $idTel;
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
                return $idUsername;
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
    public function save_record(
        $idUser, 
        $website, 
        $username, 
        $email, 
        $tel, 
        $password, 
        $recoveryKey
        ) {
        //array de datos
        $data = [
            'id_usuario' => $idUser,
            'id_portal_registro' => $website,
            'id_correo_registro' => $email,
            'id_nombre_registro' => $username,
            'id_telefono_registro' => $tel,
            'contrasenia' => $password,
            'clave_recuperacion' => $recoveryKey
        ];

        //llamada método validate website name 'HomeModel'
        if ($website !== null) {
            $idWebsite = $this->validate_website_name($website);
            $data['id_portal_registro'] = $idWebsite->id;
        }

        //llamada método validate email 'HomeModel'
        if ($email !== null) {
            $idEmail = $this->validate_email($email);
            $data['id_correo_registro'] = $idEmail->id;
        }

        //llamada método validate nombre usuario 'HomeModel'
        if ($username !== null) {
            $idUsername = $this->validate_username($username);
            $data['id_nombre_registro'] = $idUsername->id;
        }

        //llamada método validate telefono 'HomeModel'
        if ($tel !== null) {
            $idTel = $this->validate_tel($tel);
            $data['id_telefono_registro'] = $idTel->id;
        }

        //eliminación de datos null, para ejecución de consulta preparada
        foreach ($data as $key => $value) {
            if ($value === null){
                unset($data[$key]);
            }
        }

        /*
        Construcción dinámica de campos
        y marcadores, para la posterior 
        elaboración de consulta sql
        */
        $field = '';
        $marker = '';
        foreach ($data as $key => $value) {
            $field .= ($field ? ', ' : '') . $key;
            $marker .= ($marker ? ', ' : '') . ":$key";
        }

        //sentencia sql elaborada
        $sql = "INSERT INTO dato_registro ($field) VALUES ($marker)";

        //insertar datos
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