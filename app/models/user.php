<?php
class UserModel {
    private PDO $connection;

    #constructor
    public function __construct(PDO $connection) {
        $this->connection = $connection;
    }

        //validar existencia de correo de usuario, y retorna datos
        public function validate_email_user($email) {
            try {
                $prepare = $this->connection->prepare('SELECT * FROM usuario WHERE correo = ?');
                $prepare->execute(array($email));
                return $result = $prepare->fetch(PDO::FETCH_OBJ);
            } catch (PDOException $e) {
                die('Error: ' . $e->getMessage());
            }
        }
    
        //validar datos de inicio de sesión
        public function validate_sign_in($email, $password, $id) {
            try {
                //preparar consulta para obtener hash de usuario
                $prepare = $this->connection->prepare('SELECT contrasenia FROM usuario WHERE id = ?');
                $prepare->execute(array($id));
                $data = $prepare->fetch(PDO::FETCH_OBJ);

                //validar si devuelve datos
                if ($data) {
                    if (password_verify($password, $data->contrasenia)) {
                        return true;
                    } else {
                        return false;
                    }
                }
            } catch (PDOException $e) {
                die('Error: ' . $e->getMessage());
            }
        }

    //registrar un nuevo usuario
    public function sign_up($userName, $email, $password){
        try {
            //validar existencia de dirección de correo electrónico
            if (!$this->validate_email_user($email)) {
                //preparar consulta
                $prepare = $this->connection->prepare('
                    INSERT INTO
                        usuario (nombre, correo, contrasenia) 
                    VALUES
                        (
                            ? , ? , ? 
                        )
                    ');
                //ejecutar consulta preparada
                $prepare->execute(array($userName, $email, $password));
                //evaluar si existen columnas afectadas
                if ($prepare->rowCount()) {
                    return 'exito';
                } else {
                    return 'falla';
                }
            } else {
                return 'correo_existente';
            }
        } catch (PDOException $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    //establecer nueva contraseña
    public function new_password($idUser, $password) {
        //encriptamos contraseña
        $password = password_hash($password, PASSWORD_DEFAULT);

        //insertar nueva contraseña
        try {
            $prepare = $this->connection->prepare('UPDATE usuario set contrasenia = ? WHERE id = ?');
            $prepare->execute([$password, $idUser]);
            return $prepare->rowCount();
        } catch (PDOException $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    //obtener datos de un usuario
    public function user_data($email){
        try {
            $prepare = $this->connection->prepare('SELECT * FROM user WHERE correo = ?');
            $prepare->execute(array($email));
            return $results = $prepare->fetch(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            die('Error: ' .$e->getMessage());
        }
    }
}