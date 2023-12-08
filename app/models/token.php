<?php
class TokenModel {
    private ?PDO $connection = null;

    public function __construct(PDO $connection) {
        $this->connection = $connection;
    }

    //método para generar un token
    private function generateToken() {
        //longitud maxima de token
        $length = 60;
        //posibles caracteres para generar token
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ#!@%$';
        //definición de variable token
        $token = '';
        //construcción de token con ciclo
        for ($i = 0; $i < $length; $i++){
            $token .= $chars[random_int(0, strlen($chars) -1)];
        }
        //retorno de token
        return $token;
    }

    //obtener token
    public function search_token($idUser) {
        //obtener token
        try {
            $prepare = $this->connection->prepare('SELECT * FROM solicitud_restablecer_contrasenia WHERE id_usuario = ?');
            $prepare->execute(array($idUser));
            return $result = $prepare->fetch(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            die('Error: ' .$e->getMessage());
        }
    }

    public function save_new_token($idUser) {
        //llamar método para generar token
        $token = $this->generateToken();
        //llamar método para crear fecha de expiración
        $tokenExpiration = $this->token_expiration_date();
        //inserción de nuevo token
        try {
            $prepare = $this->connection->prepare('INSERT INTO solicitud_restablecer_contrasenia (id_usuario, token, fecha_expiracion) VALUES (?, ?, ?)');
            $prepare->execute(array($idUser, $token, $tokenExpiration));
            //validar si se insertaron filas
            return $prepare->rowCount();
        } catch (PDOException $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    private function token_expiration_date() {
        //obtenemos fecha y hora actual
        $date = date('Y-m-d H:i:s');
        //asignar tiempo de expiración (5h)
        return $dateExpiration = date('Y-m-d H:i:s', strtotime($date . '+' . 5 . ' hours'));
    }

    //validar token
    public function validate_token($idUser, $token) {
        //obtener token
        if ($data = $this->search_token($idUser)){

            //comparar tokens
            if ($token === $data->token) {
                //almacenar fecha de token
                $expirationDate = $data->fecha_expiracion;
                //obtener fecha actual
                $currentDate = date('Y-m-d H:i:s');
                
                //validar fecha expiración token
                if (!($currentDate > $expirationDate)) {
                    return 'proceder_cambio';
                } else {
                    return 'token_expiro';
                }
            } else {
                return 'token_invalido';;
            }
        } else {
            return 'usuario_no_encontrado';
        }
    }
}