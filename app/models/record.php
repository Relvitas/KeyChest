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

    // Método encargado de obtener la paginación
    public function pagination($idUser, $pageLimit) {
        /*
        Sentencia sql utilizada para obtener todos
        los registros/credenciales de acceso del usuario
        para establecer una paginación 
        */
        $sql = '
            SELECT
                portal_registro.nombre as sitio_web,
                correo_registro.correo,
                nombre_registro.nombre as nombre_usuario,
                telefono_registro.telefono,
                dato_registro.contrasenia,
                dato_registro.clave_recuperacion 
            FROM
                dato_registro 
            INNER JOIN
                portal_registro ON dato_registro.id_portal_registro = portal_registro.id 
            LEFT JOIN
                correo_registro ON dato_registro.id_correo_registro = correo_registro.id 
            LEFT JOIN
                nombre_registro ON dato_registro.id_nombre_registro = nombre_registro.id 
            LEFT JOIN
                telefono_registro ON dato_registro.id_telefono_registro = telefono_registro.id 
            WHERE
                dato_registro.id_usuario = ?;
        ';
        try {
            $prepare = $this->connection->prepare($sql);
            $prepare->execute([$idUser]);
            //validar si se obtuvieron datos
            if ($numberRows = $prepare->rowCount()) {
                return $totalPages = ceil($numberRows/$pageLimit); //obtener total de paginaciones
            }
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    // Método encargado de retornar los registros del usuario
    public function list_record($idUser, $startFrom, $pageLimit) {
        /*
        Sentencia SQL encarga de consultar los
        registros/credenciales de acceso de los
        diferentes sitios web del usuario
        */
        $sql = '
            SELECT
                dato_registro.id as id_registro,
                portal_registro.nombre as sitio_web,
                correo_registro.correo,
                nombre_registro.nombre as nombre_usuario,
                telefono_registro.telefono,
                dato_registro.contrasenia,
                dato_registro.clave_recuperacion 
            FROM
                dato_registro 
            INNER JOIN
                portal_registro ON dato_registro.id_portal_registro = portal_registro.id 
            LEFT JOIN
                correo_registro ON dato_registro.id_correo_registro = correo_registro.id 
            LEFT JOIN
                nombre_registro ON dato_registro.id_nombre_registro = nombre_registro.id 
            LEFT JOIN
                telefono_registro ON dato_registro.id_telefono_registro = telefono_registro.id 
            WHERE
                dato_registro.id_usuario = ?
            LIMIT
                ?, ?
        ';
        try {
            $prepare = $this->connection->prepare($sql);
            $prepare->bindParam(1, $idUser, PDO::PARAM_INT);
            $prepare->bindParam(2, $startFrom, PDO::PARAM_INT);
            $prepare->bindParam(3, $pageLimit, PDO::PARAM_INT);
            $prepare->execute();
            return $prepare->fetchAll(PDO::FETCH_OBJ);
        } catch(PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function edit_record($idRecord) {
        /*
        Sentencia sql encargada de mostrar un registro en especifico
        para que el usuario pueda proceder con la edición del mismo
        */
        $sql = '
            SELECT
                portal_registro.id as id_portal_registro,
                portal_registro.nombre as sitio_web,
                correo_registro.id as id_correo_registro,
                correo_registro.correo,
                nombre_registro.id as id_nombre_registro,
                nombre_registro.nombre as nombre_usuario,
                telefono_registro.id as id_telefono_registro,
                telefono_registro.telefono,
                dato_registro.id as id_registro,
                dato_registro.contrasenia,
                dato_registro.clave_recuperacion 
            FROM
                dato_registro 
            INNER JOIN
                portal_registro ON dato_registro.id_portal_registro = portal_registro.id 
            LEFT JOIN
                correo_registro ON dato_registro.id_correo_registro = correo_registro.id 
            LEFT JOIN
                nombre_registro ON dato_registro.id_nombre_registro = nombre_registro.id 
            LEFT JOIN
                telefono_registro ON dato_registro.id_telefono_registro = telefono_registro.id 
            WHERE
                dato_registro.id = ?
        ';
        try {
            $prepare = $this->connection->prepare($sql);
            $prepare->bindParam(1, $idRecord, PDO::PARAM_INT);
            $prepare->execute();
            $record = $prepare->fetch(PDO::FETCH_OBJ);
            if ($record) {
                return $record;
            }
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Comprueba la existencia de un registro y devuelve su identificador.
     *
     * @param string $sql Consulta SQL preparada que debe contener un marcador de posición.
     * @param string $record Nombre del registro que se está buscando.
     * 
     * @return int|bool El identificador del registro si existe, o false si no se encuentra.
     */
    public function search_a_record($sql, $record) {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([$record]);
            $row = $stmt->fetchColumn();
            return ($row != false) ? $row : false;
        } catch (PDOException $e) {

            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Método encargado de eliminar un registro.
     * 
     * @param string $sql Consulta SQL preparada que debe contener un marcador de posición.
     * @param string $record Nombre del registro que se está buscando.
     * 
     * @return bool True si la eliminación fue exitosa, false en caso contrario.
     */
    private function delete_a_record($sql, $idRecord) {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([$idRecord]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {

            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Elimina un registro sin relaciones existentes.
     *
     * Esta función busca si un registro tiene alguna relación en tabla 'dato_registro'.
     * Si no hay relaciones, elimina el registro correspondiente; de lo contrario, no realiza ninguna acción.
     * 
     * @param string $sql Consulta SQL preparada que debe contener un marcador de posición.
     * @param int $idRecord Identificador del registro que se eliminara.
     * 
     * @return bool True si la operación fue un éxito, false en caso contrario
     */
    public function delete_unrelated_record($sql, $idRecord) {
        try {
            $prepare = $this->connection->prepare($sql);
            $prepare->execute([$idRecord]);
            $row = $prepare->fetchColumn();
            
            // validamos si se encontró registro
            if ($row != false) {
                return $row;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Actualiza un campo especifico en la tabla pivot.
     *
     * @param string $sql Consulta SQL preparada que debe contener un marcador de posición.
     * @param int $idRegistro Identificador único del registro a actualizar.
     * @param mixed $nuevoValor Nuevo valor para el campo 'id_nombre_registro'.
     *
     * @return bool Devuelve true si la actualización fue exitosa, false en caso contrario.
     */
    private function update_a_pivot_table_record($sql, $existingUsername, $pivotRecordId){
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([$existingUsername, $pivotRecordId]);
        
            /**
             * Validar si la actualización del nombre fue exitosa en tabla pivot.
             */
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Actualiza un registro en la base de datos.
     *
     * @param string $sql Consulta SQL preparada que debe contener un marcador de posición para la actualización.
     * @param string $newRecord Contiene el registro actualizado.
     * @param int $idRecord Identificador del registro específico al campo que se desea actualizar.
     * 
     * @return int|null Devuelve 1 si se actualizaron columnas, devuelve null si no se actualizó nada.
     */
    private function update_a_record($sql, $newRecord, $idRecord){
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([$newRecord, $idRecord]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {

            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Inserta un registro
     *
     * @param string $sql Consulta SQL preparada que debe contener un marcador de posición para la actualización.
     * @param string $newDate Contiene el registro nuevo que se quiere insertar en base de datos
     * 
     * @return bool|int Devuelve el ID de el ultimo dato insertado en base de datos, o false en caso de error
     */
    private function insert_a_record($sql, $newDate) {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([$newDate]);
            if ($stmt->rowCount()) {
                return $this->connection->lastInsertId();
            }
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Actualiza el nombre de usuario
     *
     * Esta función se utiliza para actualizar el nombre de un usuario en la base de datos,
     * realizando validaciones como la existencia previa del usuario. Además, maneja la eliminación
     * de registros no relacionados.
     *
     * @param int $idUsername - El ID del registro original
     * @param string $newUsername - El nuevo nombre de usuario a insertar o actualizar.
     * @param int $pivotRecordId - El ID del registro en la tabla pivot
     *
     * @return bool - true si la actualización fue exitosa, false si existió algún error
     */
    public function update_username($idUsername, $newUsername, $pivotRecordId) {
        /**
         * Validar si el nombre esta presente en base de datos
         */
        if ($existingUsername = $this->search_a_record(
            'SELECT id FROM nombre_registro WHERE nombre = ?',
            $newUsername
        )) {
            /**
             * Actualiza el identificador 'id_nombre_usuario' en la tabla pivot 'dato_registro'
             * si el registro está presente en la base de datos.
             */
            if ($this->update_a_pivot_table_record(
                'UPDATE dato_registro SET id_nombre_registro = ? WHERE id = ?',
                $existingUsername, 
                $pivotRecordId
            )) {
                /**
                 * Verifica si el registro anterior, después de la actualización, 
                 * mantiene alguna relación. Si no existe una relación, 
                 * se procederá con la eliminación del registro
                 */
                if ($idUnrelated = $this->delete_unrelated_record(
                    $sql = '
                        SELECT
                            nombre_registro.id
                        FROM
                            dato_registro
                            RIGHT JOIN nombre_registro ON dato_registro.id_nombre_registro = nombre_registro.id
                        WHERE
                            dato_registro.id IS NULL
                            AND nombre_registro.id = ?
                    ',    
                    $idUsername
                )) {
                    // Eliminación del registro
                    return $this->delete_a_record(
                        'DELETE FROM nombre_registro WHERE id = ?',
                        $idUnrelated
                    );
                }
            }
        } else {
            if (!$newUsernameId = $this->insert_a_record(
                'INSERT INTO nombre_registro (nombre) VALUES (?)',
                $newUsername
            )) {
                return false;
            } else {
                /**
                 * Actualizamos el registro en la tabla pivot
                 * una ves sea insertado el nuevo registro
                 */
                return $this->update_a_pivot_table_record(
                    'UPDATE dato_registro SET id_nombre_registro = ? WHERE id = ?',
                    $newUsernameId, 
                    $pivotRecordId
                );
            }
        }
    }

    /**
     * Actualiza el nombre de un sitio web y su entrada correspondiente en la tabla pivot.
     *
     * Esta función gestiona la actualización del nombre de un sitio web en la base de datos,
     * garantizando la existencia previa del usuario. También se encarga de la eliminación
     * de registros no relacionados y actualiza el identificador en la tabla pivot.
     *
     * @param int $idWebsite - ID del registro original.
     * @param string $neWebsiteName - Nuevo nombre del sitio web a insertar o actualizar.
     * @param int $pivotRecordId - ID del registro en la tabla pivot.
     *
     * @return bool - True si la actualización fue exitosa, false si hubo algún error.
     */
    public function update_website($idWebsite, $neWebsite, $pivotRecordId) {
        /**
         * Validar si el nombre esta presente en base de datos
         */
        if ($existingWebsite = $this->search_a_record(
            'SELECT id FROM portal_registro WHERE nombre = ?',
            $neWebsite
        )) {
            /**
             * Actualiza el identificador 'id_portal_usuario' en la tabla pivot 'dato_registro'
             * si el registro está presente en la base de datos.
             */
            if ($this->update_a_pivot_table_record(
                'UPDATE dato_registro SET id_portal_registro = ? WHERE id = ?',
                $existingWebsite, 
                $pivotRecordId
            )) {
                /**
                 * Verifica si el registro anterior, después de la actualización, 
                 * mantiene alguna relación. Si no existe una relación, 
                 * se procederá con la eliminación del registro
                 */
                if ($idUnrelated = $this->delete_unrelated_record(
                    $sql = '
                        SELECT
                            portal_registro.id
                        FROM
                            dato_registro
                            RIGHT JOIN portal_registro ON dato_registro.id_portal_registro = portal_registro.id
                        WHERE
                            dato_registro.id IS NULL
                            AND portal_registro.id = ?
                    ',    
                    $idWebsite
                )) {
                    // Eliminación del registro
                    return $this->delete_a_record(
                        'DELETE FROM portal_registro WHERE id = ?',
                        $idUnrelated
                    );
                }
            }
        } else {
            if (!$neWebsiteId = $this->insert_a_record(
                'INSERT INTO portal_registro (nombre) VALUES (?)',
                $neWebsite
            )) {
                return false;
            } else {
                /**
                 * Actualizamos el registro en la tabla pivot
                 * una ves sea insertado el nuevo registro
                 */
                return $this->update_a_pivot_table_record(
                    'UPDATE dato_registro SET id_portal_registro = ? WHERE id = ?',
                    $neWebsiteId, 
                    $pivotRecordId
                );
            }
        }
    }

    /**
     * Actualiza un correo electrónico y su entrada correspondiente en la tabla pivot.
     *
     * Esta función gestiona la actualización del correo en la base de datos,
     * garantizando la existencia previa del usuario. También se encarga de la eliminación
     * de registros no relacionados y actualiza el identificador en la tabla pivot.
     *
     * @param int $idEmail - ID del registro original.
     * @param string $newEmail - Nuevo correo a insertar o actualizar.
     * @param int $pivotRecordId - ID del registro en la tabla pivot.
     *
     * @return bool - True si la actualización fue exitosa, false si hubo algún error.
     */
    public function update_email($idEmail, $newEmail, $pivotRecordId) {
        /**
         * Validar si el nombre esta presente en base de datos
         */
        if ($existingEmail = $this->search_a_record(
            'SELECT id FROM correo_registro WHERE correo = ?',
            $newEmail
        )) {
            /**
             * Actualiza el identificador 'id_correo_usuario' en la tabla pivot 'dato_registro'
             * si el registro está presente en la base de datos.
             */
            if ($this->update_a_pivot_table_record(
                'UPDATE dato_registro SET id_correo_registro = ? WHERE id = ?',
                $existingEmail, 
                $pivotRecordId
            )) {
                /**
                 * Verifica si el registro anterior, después de la actualización, 
                 * mantiene alguna relación. Si no existe una relación, 
                 * se procederá con la eliminación del registro
                 */
                if ($idUnrelated = $this->delete_unrelated_record(
                    $sql = '
                        SELECT
                            correo_registro.id
                        FROM
                            dato_registro
                            RIGHT JOIN correo_registro ON dato_registro.id_correo_registro = correo_registro.id
                        WHERE
                            dato_registro.id IS NULL
                            AND correo_registro.id = ?
                    ',    
                    $idEmail
                )) {
                    // Eliminación del registro
                    return $this->delete_a_record(
                        'DELETE FROM correo_registro WHERE id = ?',
                        $idUnrelated
                    );
                }
            }
        } else {
            if (!$newEmailId = $this->insert_a_record(
                'INSERT INTO correo_registro (correo) VALUES (?)',
                $newEmail
            )) {
                return false;
            } else {
                /**
                 * Actualizamos el registro en la tabla pivot
                 * una ves sea insertado el nuevo registro
                 */
                return $this->update_a_pivot_table_record(
                    'UPDATE dato_registro SET id_correo_registro = ? WHERE id = ?',
                    $newEmailId, 
                    $pivotRecordId
                );
            }
        }
    }

    /**
     * Actualiza un numero de telefono y su entrada correspondiente en la tabla pivot.
     *
     * Esta función gestiona la actualización del telefono en la base de datos,
     * garantizando la existencia previa del usuario. También se encarga de la eliminación
     * de registros no relacionados y actualiza el identificador en la tabla pivot.
     *
     * @param int $idTel - ID del registro original.
     * @param string $newTel - Nuevo telefono a insertar o actualizar.
     * @param int $pivotRecordId - ID del registro en la tabla pivot.
     *
     * @return bool - True si la actualización fue exitosa, false si hubo algún error.
     */
    public function update_tel($idTel, $newTel, $pivotRecordId) {
        /**
         * Validar si el nombre esta presente en base de datos
         */
        if ($existingTel = $this->search_a_record(
            'SELECT id FROM telefono_registro WHERE telefono = ?',
            $newTel
        )) {
            /**
             * Actualiza el identificador 'id_telefono_usuario' en la tabla pivot 'dato_registro'
             * si el registro está presente en la base de datos.
             */
            if ($this->update_a_pivot_table_record(
                'UPDATE dato_registro SET id_telefono_registro = ? WHERE id = ?',
                $existingTel, 
                $pivotRecordId
            )) {
                /**
                 * Verifica si el registro anterior, después de la actualización, 
                 * mantiene alguna relación. Si no existe una relación, 
                 * se procederá con la eliminación del registro
                 */
                if ($idUnrelated = $this->delete_unrelated_record(
                    $sql = '
                        SELECT
                            telefono_registro.id
                        FROM
                            dato_registro
                            RIGHT JOIN telefono_registro ON dato_registro.id_telefono_registro = telefono_registro.id
                        WHERE
                            dato_registro.id IS NULL
                            AND telefono_registro.id = ?
                    ',    
                    $idEmail
                )) {
                    // Eliminación del registro
                    return $this->delete_a_record(
                        'DELETE FROM telefono_registro WHERE id = ?',
                        $idUnrelated
                    );
                }
            }
        } else {
            if (!$newTelId = $this->insert_a_record(
                'INSERT INTO telefono_registro (telefono) VALUES (?)',
                $newTel
            )) {
                return false;
            } else {
                /**
                 * Actualizamos el registro en la tabla pivot
                 * una ves sea insertado el nuevo registro
                 */
                return $this->update_a_pivot_table_record(
                    'UPDATE dato_registro SET id_telefono_registro = ? WHERE id = ?',
                    $newTelId, 
                    $pivotRecordId
                );
            }
        }
    }

    /**
     * Actualiza la contraseña en la tabla pivot de un registro especifico
     *
     * @param string $newPassword - Nueva contraseña a insertar o actualizar.
     * @param int $pivotRecordId - ID del registro en la tabla pivot.
     *
     * @return bool - True si la actualización fue exitosa, false si hubo algún error.
     */
    private function update_password($newPassword, $pivotRecordId) {
        $sql = 'UPDATE dato_registro SET contrasenia = ? WHERE id = ?';
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([$newPassword, $pivotRecordId]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Actualiza la clave de recuperación en la tabla pivot de un registro especifico
     *
     * @param string $newRecoveryKey - Nueva clave de recuperación a insertar o actualizar.
     * @param int $pivotRecordId - ID del registro en la tabla pivot.
     *
     * @return bool - True si la actualización fue exitosa, false si hubo algún error.
     */
    private function update_recovery_key($newRecoveryKey, $pivotRecordId) {
        $sql = 'UPDATE dato_registro SET clave_recuperacion = ? WHERE id = ?';
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([$newRecoveryKey, $pivotRecordId]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Actualizar un registro completo
     * 
     * @param array $data Contendrá todos los datos que son enviados via POST
     * 
     * @return bool|string 
     */
    public function update_record(array $data) {
        /**
         * Desestructuración del array $data para acceder 
         * a sus elementos individualmente.
         * Esto facilita la manipulación y uso de datos 
         * específicos dentro del array.
         */
        [
            'id_record'   =>  $pivotRecordId,
            'id_website'  =>  $idWebsite,
            'id_username' =>  $idUsername,
            'id_email'    =>  $idEmail,
            'id_tel'      =>  $idTel,
            'website'     =>  $website,
            'password'    =>  $password,
            'username'    =>  $username,
            'tel'         =>  $tel,
            'email'       =>  $email,
            'recoveryKey' =>  $recoveryKey
        ] = $data;

        // Definición de la variable $message que almacenará mensajes de error
        $message = '';

        /**
         * $flag: Bandera utilizada para validar y dar inicio a secciones 
         * específicas de código.
         */
        $flag = true;

        /**
         * Compara los campos del formulario con los 
         * registros de la base de datos.
         * Cada ID del formulario debe coincidir con el 
         * correspondiente en la base de datos.
         * de lo contrario la bandera pasara a ser false
         * impidiendo que el siguiente código se ejecute
         */
        if ($records = $this->edit_record($data['id_record'])) {
            foreach ($records as $record => $value) {
                if (
                    ($record === 'id_portal_registro' && $value != $idWebsite) ||
                    ($record === 'id_correo_registro' && $value != $idEmail) ||
                    ($record === 'id_nombre_registro' && $value != $idUsername) ||
                    ($record === 'id_telefono_registro' && $value != $idTel) ||
                    ($record === 'id_registro' && $value != $pivotRecordId)
                ) {
                    $message = 'rellene correctamente el formulario';
                    $flag = false;
                    break;
                }
            }
        }

        /**
         * Se ejecuta el código solo si la bandera es verdadera.
         */
        if (!$flag) {
            return $message;
        
        } else {
            /**
             * Actualiza los datos de la tabla 'dato_registro' dentro del array $records evaluando cada elemento.
             *
             * Se aplicará una función específica para cada dato, deteniéndose en caso de error.
             * Si alguna evaluación resulta falsa, se interrumpe la actualización.
             */
            foreach ($records as $record => $value) {
                switch ($record) {
                    case 'sitio_web':
                        if ($value == $website || $website == ''){
                            break;
                        } else {
                            if (!$this->update_website($idWebsite, $website, $pivotRecordId)) {
                                $message = 'error actualización nombre sitio web';
                                break 2;
                            }
                            break;
                        }
                    case 'nombre_usuario':
                        if ($value == $username || $username == ''){
                            break;
                        } else {
                            if (!$this->update_username($idUsername, $username, $pivotRecordId)) {
                                $message = 'error actualización nombre usuario';
                                break 2;
                            }
                            break;
                        }
                    case 'correo':
                        if ($value == $email || $email == ''){
                            break;
                        } else {
                            if (!$this->update_email($idEmail, $email, $pivotRecordId)) {
                                $message = 'error actualización correo';
                                break 2;
                            }
                            break;
                        }
                    case 'telefono':
                        if ($value == $tel || $tel == ''){
                            break;
                        } else {
                            if (!$this->update_tel($idTel, $tel, $pivotRecordId)) {
                                $message = 'error actualización telefono';
                                break 2;
                            }
                            break;
                        }
                    case 'contrasenia':
                        if ($value == $password || $password == ''){
                            break;
                        } else {
                            if (!$this->update_password($password, $pivotRecordId)) {
                                $message = 'error actualización contraseña';
                                break 2;
                            }
                            break;
                        }
                    case 'clave_recuperacion':
                        if ($value == $recoveryKey || $recoveryKey == ''){
                            break;
                        } else {
                            if (!$this->update_recovery_key($recoveryKey, $pivotRecordId)) {
                                $message = 'error actualización clave recuperación';
                                break 2;
                            }
                            break;
                        }
                }
            }
            /**
             * Verifica si el mensaje de error está vacío.
             * Devuelve variable $message si no hay error, de lo contrario, retorna true.
             */
            if ($message != '') {
                return $message;
            } else {
                return true;
            }
        }
    }
}