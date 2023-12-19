<?php
class RecordController {
    private RecordModel $recordModel;

    public function __construct(){
        //llamada modelo 'new_record'
        require_once 'models/record.php';

        //instancia 'HomeModel'
        $this->recordModel = new RecordModel(
            //dependencia conexión db
            Connection::connection()
        );
    }

    // Método encargado de almacenar registros
    public function save_record(){
        /*
        Evaluar existencia de sesión de usuario
        en caso de que esta no exista la vista
        que cargara sera la de iniciar sesión
        */
        session_start();
        if (!isset($_SESSION['user']['id'])) {
            $this->view('sign_in');

        } else { 
            /* 
            $flag es utilizada dentro de bucles
            y condicionales para evaluar si se
            debe ejecutar código
            */
            $flag = true;

            /* 
            $message, contendrá el mensaje error 
            que sera el argumento a pasar a la función view()
            */
            $message = '';

            /*
            $data, array que tendrá en su interior
            toda la información que se esta enviando via
            POST del formulario de nuevo registro 'home' y
            ademas de esta tendrá una configuración inicial que
            almacenara el id de usuario de sesión
            */
            $data = ['idUser' => $_SESSION['user']['id']];

            /* 
            $fields array contenedor de los posibles campos 
            via POST del formulario de home.php
            */
            $fields = [
                'save_record',
                'website', 
                'password', 
                'username', 
                'tel', 
                'email', 
                'recoveryKey'
            ];

            /*
            Bucle encargado de evaluar si las variables
            POST están definidas
            */
            foreach ($fields as $field) {
                if(!isset($_POST[$field])){
                    $flag = false;
                }
            }

            /*
            Mostrar vista home en la cual se encuentra
            el formulario para realizar nuevos registros
            */
            if (!$flag) {
                $this->view('home');

            // Si todo esta bien
            } else {
                /*
                Construcción final ($data), este tendrá toda
                los datos post necesarios y formateados para el
                posterior uso, este bucle contiene $flag
                */
                foreach ($fields as $field) {

                    switch ($field) {
                        case 'website':
                        case 'username':

                            // Primer carácter en mayúscula resto en minúscula
                            $data[$field] = !empty($_POST[$field]) 
                                ? ucfirst(strtolower($_POST[$field]))
                                : '';
                            break;
                        case 'email':
                            
                            // Minúsculas
                            $data[$field] = !empty($_POST[$field])
                                ? strtolower($_POST[$field])
                                : '';
                            break;
                        case 'tel':
                            /* Validaciones para el teléfono.
                            Se establece regla de longitud y regla de
                            que contenga solo números
                            */
                            if (!empty($_POST[$field])) {

                                if (!(strlen($_POST[$field]) <= 25)) {
                                    // Uso de bandera
                                    $flag = false;
                                    // Mensaje con tipo error
                                    $message = 'supera_longitud';
                                    break 2;
                                }
                
                                if (!ctype_digit($_POST[$field])) {
                                    // Uso de bandera
                                    $flag = false;
                                    // Mensaje con tipo error
                                    $message = 'numero_invalido';
                                    break 2;
                                }
                                
                                $data[$field] = $_POST[$field];
                            } else {
                                $data[$field] = '';
                            }
                            break;
                        case 'recoveryKey':

                            $data[$field] = !empty($_POST[$field])
                                ? $_POST[$field]
                                : '';
                            break;
                        case 'password':
                            /*
                            Validaciones para la contraseña
                            esta no debe superar el limite de 255
                            caracteres y es obligatorio que el campo
                            sea rellenado
                            */
                            if (!empty($_POST[$field])){

                                if (strlen($_POST[$field]) > 255) {
                                    // Uso de bandera
                                    $flag = false;
                                    // Mensaje con tipo error
                                    $message = 'contraseña_invalida';
                                    break 2;
                                }
    
                                $data[$field] = $_POST[$field];
                            } else {

                                // Uso de bandera
                                $flag = false;
                                // mensaje tipo error
                                $message = 'campos_requeridos';
                                break 2;
                            }
                    }
                }
                /*
                Mostrar vista home en la cual se encuentra el formulario 
                para realizar nuevos registros, dado a que el usuario 
                no relleno los campos obligatorios
                */
                if (!$flag) {
                    $this->view('home', $message);
                
                // Si todo esta bien
                } else {
                    /*
                    Se hace llamada del método save_record
                    de la clase RecordModel para realizar una nueva
                    inserción de registro pasando como argumento $data
                    array contenedor de configuración de información
                    y evaluamos resultados
                    */
                    if ($this->recordModel->save_record($data)) {
                        $this->view('home', 'exito');
                    } else {
                        $this->view('home', 'error');
                    }
                }
            }      
        }
    }

    // Método seleccionador de vista
    public function view($view, $info = null) {

        //array de posibles vistas de primera vista (acceso)
        $views = [
            'reset_password', 
            'new_password',
            'sign_in', 
            'sign_up'
        ];

        //cargar el header según la vista seleccionada
        if (in_array($view, $views)) {
            require_once 'partials/header_access.php';
        } else {
            require_once 'partials/header.php';
        }

        //cargar la vista según la vista seleccionada
        require_once 'views/' . $view . '.php';

        //cargar el footer según la vista seleccionada
        if (in_array($view, $views)) {
            require_once 'partials/footer_access.php';
        } else {
            require_once 'partials/footer.php';
        }
        
    }
}