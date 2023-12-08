<?php
class Connection {
    //fichero de configuración
    private static string $file = 'config/config.ini';

    //método de conexión
    public static function connection() {
        //validar existencia fichero ini de configuración
        if (file_exists(self::$file)) {
            //lectura de archivo ini
            $config = parse_ini_file(self::$file, true);

            //desestructuración de array 
            [
                'db_driver' => $driver,
                'db_user' => $user,
                'db_password' => $password
            ] = $config;
            
            //construcción inicial de dsn
            $dsn = $driver . ':';
            
            //construcción dsn completa
            foreach ($config['dsn'] as $key => $value) {
                if ($key === 'port') {
                    continue;
                } else {
                    if ($key === 'charset') {
                        $dsn .= $key . '=' . $value;
                    } else {
                        $dsn .= $key . '=' . $value . ';';
                    }
                }
            }
            
            //construcción de atributos
            foreach ($config['attributes'] as $key => $value) {
                $attributes = [
                    'PDO::' . $key => $value
                ];
            }

            //conexión con db
            try {
                return $connection = new PDO($dsn, $user, $password, $attributes);
            } catch (PDOException $e) {
                die('Error: ' . $e->getMessage());
            }
        } else {
            return 'missing file';
        }
    }
}