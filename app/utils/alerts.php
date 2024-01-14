<?php
/**
 * Maneja las alertas del sistema.
 *
 * @param string|bool $alert Se espera un string o true como argumento.
 * 
 * @return string Retorna un mensaje personalizado según el tipo de alerta.
 */
function alert($alert){
    // Evaluamos si se recaven datos
    if ($alert == true) {
        // $negative_alert sera la variable contenedor de el string alerta negativa
        $negative_alert = '
        <div class="alert">
            <div class="alert-false">
            <i class="fa-solid fa-circle-exclamation"></i><p>' . $alert . '</p>
            </div>
        </div>
        ';

        // $positive_alert sera la variable contenedor de el string alerta positiva
        $positive_alert = '
        <div class="alert">
            <div class="alert-true">
                <i class="fa-solid fa-circle-check"></i>><p>' . $alert . '</p>
            </div>
        </div>
        ';

        /**
         * Realiza una evaluación de casos posibles y devuelve una alerta, 
         * ya sea positiva o negativa.
         * 
         * @var string $alert Contendrá el mensaje de la alerta a evaluar.
         */
        switch ($alert) {
            case 'Clave incorrecta':
            case 'Correo inexistente':
            case 'Datos incompletos':
            case 'Acceso incorrecto':
            case 'Campo incompleto':
            case 'Correo existente':
            case 'Acceso incorrecto':
            case 'Falla sistema':
            case 'Correo invalido':
                return $negative_alert;
                break;
            case 'Éxito':
            case 'Correo enviado':
                return $positive_alert;
                break;
        }
    }
}