<?php
error_reporting(E_ALL);

function log_error($errno, $errstr, $errfile, $errline) {
    //ruta del archivo log
    $path = 'logs/error_log.txt';

    //lectura contenido actual de fichero log
    $aboveContent = file_get_contents($path);

    $date = date('Y-m-d: H-i-s');

    //mensaje error configurado
    $message = <<<EOT
    NEW ERROR {
    $date
    error_level: [$errno]
    error_description: $errstr
    error_file: $errfile
    error_line: $errline
    }


    EOT;

    //abrir fichero en modo escritura y truncar
    $file = fopen($path, 'w');

    //escribir mensaje y luego agregar contenido anterior
    fwrite($file, $message . $aboveContent);

    //cerrar archivo
    fclose($file);
}

set_error_handler("log_error");