<?php
class RecordController {
    private RecordModel $recordModel;

    public function __construct(){
        //llamada modelo 'new_record'
        require_once 'models/home.php';

        //instancia 'HomeModel'
        $this->recordModel = new RecordModel(
            //dependencia conexión db
            Connection::connection()
        );
    }

    /**
     * este método almacena un nuevo registro
     */
    public function save_record(){
        //validar si se pulsa botón 'save_record' 
        if (isset($_POST['save_record'])) {
            // Campos POST
            $fields = ['website', 'password', 'username', 'tel', 'email', 'recoveryKey'];
            $data = []; //array para almacenar datos POST
            //iterar array $fields
            foreach ($fields as $field) {
                // evaluar si los campos están definidos
                if (isset($_POST[$field])){
                    // evaluar posibles casos y si están vacíos
                    switch ($field) {
                        case 'username':
                        case 'tel':
                        case 'recoveryKey':
                        case 'email':
                            // en caso de que las variables POST estén vacías se asigna null
                            !empty($_POST[$field]) ? $data[$field] = $_POST[$field] : $data[$field] = null;
                            break;
                        case 'website':
                        case 'password':
                            // en caso de que estén vacías carga vista home por campos incompletos
                            if (empty($_POST[$field])){
                                $this->view('home', 'campos_incompletos');
                                break 2; //salir del switch y del bucle foreach
                            }
                            $data[$field] = $_POST[$field];
                            break; //salir del switch
                    }
                } else {
                    $this->view('home', 'acceso_error');
                    break; // salir del bucle foreach
                }
            }

            //restaurar sesión
            session_start();

            //almacenar idUsuario
            $data['id'] = $_SESSION['user']['id'];
            
            //llamada método insertar nuevo registro
            if ($this->recordModel->save_record(
                $data['id'],
                ucfirst(strtolower($data['website'])),
                ucfirst(strtolower($data['username'])),
                strtolower($data['email']),
                $data['tel'],
                $data['password'],
                $data['recoveryKey']
            )) {
                $this->view('home', 'exito');
            } else {
                $this->view('home', 'error_sistema');
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