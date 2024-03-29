<?php
class RecordController {
    private RecordModel $recordModel;

    public function __construct(){
        //llamada modelo 'new_record'
        require_once 'models/record.php';

        //llamada utilidad de paginación
        require_once 'utils/paginator.php';

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
                                    $message = 'Numero muy largo';
                                    break 2;
                                }
                
                                if (!ctype_digit($_POST[$field])) {
                                    // Uso de bandera
                                    $flag = false;
                                    // Mensaje con tipo error
                                    $message = 'Numero teléfono invalido';
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
                                    $message = 'Contraseña invalida';
                                    break 2;
                                }
    
                                $data[$field] = $_POST[$field];
                            } else {

                                // Uso de bandera
                                $flag = false;
                                // mensaje tipo error
                                $message = 'Campos requeridos';
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
                        $this->view('home', 'Registro guardado');
                    } else {
                        $this->view('home');
                    }
                }
            }      
        }
    }

    // Método para listar registros
    public function list_record() {
        session_start();
        if (!isset($_SESSION['user']['id'])) {
            $this->view('sign_in');
            
        } else {
            /*
            Validar si las variables get se están enviando
            y estas no se encuentran vacías
            */
            if (
                (!isset($_GET['c']) && empty($_GET['c'])) && 
                (!isset($_GET['a']) && empty($_GET['a']))
                ) {
                    // Carga de vista
                    $this->view('home');
            } else {
                /* 
                Almacenar id usuario para 
                un uso posterior junto con el método
                pagination 'RecordModel'
                */
                $idUser = $_SESSION['user']['id'];

                /*
                Obtener el numero de pagina seleccionada 
                por el usuario o establecer default en 1
                */
                $page = isset($_GET['p']) && !empty($_GET['p']) 
                    ? $_GET['p']
                    : 1;

                /*
                $pageLimit, establece el limite de
                cantidad de regustos maxima que queramos
                mostrar tabla
                */
                $pageLimit = 5; 

                /*
                $startForm, configuración que contiene
                el numero de pagina que se desea mostrar
                */
                $startFrom = ($page-1)*$pageLimit;

                /*
                Se obtiene el numero total de registros
                este es almacenado en $totalPages, para un
                posterior uso con la función paginator
                */
                $totalPages = $this->recordModel->pagination($idUser, $pageLimit);

                /*
                paginator (utils)Método encargado de establecer la paginación
                que vera el usuario en tabla, esta retorna una cadena
                que contiene las paginas disponibles
                */
                $pagination = paginator($page, $totalPages, '?c=record&a=list_record&p=');
                
                /*
                Se obtienen lo registros y son pasados a la
                vista junto con la paginación, para su posterior visualización
                */
                $records = $this->recordModel->list_record($idUser, $startFrom, $pageLimit);

                if ($records) {
                    $this->view('records', $records, $pagination);
                } elseif ($record === null) {
                    $this->view('home', 'Sin registros');
                } else {
                    $this->view('home');
                }
            }
        }
    }

    public function edit_record() {
        session_start();
        if (!isset($_SESSION['user']['id'])) {
            // Carga vista inicio de sesión
            $this->view('sign_in');

        } else {
            /*
            Validamos si las variables get
            se están enviando correctamente
            */
            if (
                (!isset($_GET['id']) && empty('id')) &&
                (!isset($_GET['a']) && empty($_GET['a'])) &&
                (!isset($_GET['c']) && empty($_GET['c']))
            ) {
                // Carga vista inicio sesión
                $this->view('sign_in');

            } else {
                $idRecord = $_GET['id'];

                /*
                Realizamos uso de método edit_record > RecordModel
                para obtener el registro especifico que el usuario
                quire editar
                */
                if ($record = $this->recordModel->edit_record($idRecord)) {
                    $this->view('edit_record', $record);
                } else {
                    $this->view('home', 'error_sistema');
                }
            }
        }
    }

    // Método encargado de actualizar registros
    public function update_record(){
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
                'id_record',
                'id_website',
                'id_username',
                'id_email',
                'id_tel',
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
                                    $message = 'Numero muy largo';
                                    break 2;
                                }
                
                                if (!ctype_digit($_POST[$field])) {
                                    // Uso de bandera
                                    $flag = false;
                                    // Mensaje con tipo error
                                    $message = 'Numero teléfono invalido';
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
                                    $message = 'Contraseña invalida';
                                    break 2;
                                }
    
                                $data[$field] = $_POST[$field];
                            } else {

                                // Uso de bandera
                                $flag = false;
                                // mensaje tipo error
                                $message = 'Campos requeridos';
                                break 2;
                            }
                            break;
                        case 'id_record':
                        case 'id_website':
                        case 'id_username':
                        case 'id_email':
                        case 'id_tel':

                            if (!empty($_POST[$field])) {
                                if (!ctype_digit($_POST[$field])) {
                                    // Uso de bandera
                                    $flag = false;
                                    // Mensaje con tipo error
                                    $message = $field;
                                    break 2;
                                }
                                $data[$field] = $_POST[$field];
                            } else {

                                if (!$_POST[$field] === null) {
                                    
                                    // Uso de bandera
                                    $flag = false;
                                    // mensaje tipo error
                                    $message = 'Falla sistema';
                                    break 2;
                                }

                                $data[$field] = '';
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
                    Se hace llamada del método update_record
                    de la clase RecordModel para realizar actualización
                    del registro, pasando como argumento $data
                    array contenedor de configuración de información
                    y evaluamos resultados
                    */
                    $message = $this->recordModel->update_record($data);

                    if ($message === true) {
                        $this->view('home', 'Registro actualizado');
                    } else {
                        $this->view('home', $message);
                    }
                }
            }      
        }
    }

    /**
     * Controlador que elimina un registro de la base de datos
     *
     * Este controlador llama al método delete_pivot_record en el modelo 'record'
     * para realizar la eliminación. También valida la existencia de la sesión de usuario.
     * 
     */
    public function delete_record() {
        // Validación de sesión
        session_start();
        
        if (!isset($_SESSION['user']['id'])) {

            $this->view('sign_in');
        } else {
            // Validación datos GET
            if (!isset($_GET['id_record']) && empty($_GET['id_record'])) {

                $this->view('sign_in');
            } else {
                /**
                 * Realiza una llamada al método 'delete_pivot_record' para eliminar el registro de manera segura.
                 * Este método se encarga de ejecutar la lógica necesaria para eliminar el registro asociado.
                 */
                $status = $this->recordModel->delete_pivot_record(base64_decode($_GET['id_record']));
                
                if ($status) {

                    $this->view('home', 'Registro eliminado');
                } else {

                    $this->view('home');
                }
            }
        }
    }

    /**
     * Muestra la vista de confirmación de eliminación,
     * brindando al usuario la oportunidad de confirmar
     * su decisión de eliminar los datos seleccionados.
     *
     * Este método proporciona una interfaz gráfica para que
     * el usuario confirme su acción antes de realizar la eliminación.
     */
    public function confirm_deletion() {
        // Restaurar sesión
        session_start();
        // Validar sesión
        if (!isset($_SESSION['user']['id'])) {
            $this->view('sign_in');
        } else {
            // Validar datos get
            if (!isset($_GET['id_record']) && empty($_GET['id_record'])) {
                $this->view('sign_in');
            } else {
                // Almacenar dato get
                $idRecord = $_GET['id_record'];

                // Cargar vista de confirmación
                $this->view('confirm_deletion', $idRecord);
            }
        }
    }

    /**
     * Realiza la búsqueda de un dato en la base de datos.
     *
     * Esta función se encarga de buscar un dato proporcionado por el usuario
     * en la base de datos, devolviendo los resultados correspondientes.
     */
    public function search_record() {
        // Restaurar sesión
        session_start();
        // Validar sesión
        if (!isset($_SESSION['user']['id'])) {
            $this-view('sign_in', 'Acceso incorrecto');
        } else {
            // Validar información post
            if (!isset($_POST['buscar']) && empty($_POST['buscar'])) {
                $this->view('sign_in', 'Acceso incorrecto');
            } else {

                /*
                Obtener el numero de pagina seleccionada 
                por el usuario o establecer default en 1
                */
                $page = isset($_GET['p']) && !empty($_GET['p']) 
                    ? $_GET['p']
                    : 1;

                /*
                $pageLimit, establece el limite de
                cantidad de regustos maxima que queramos
                mostrar tabla
                */
                $pageLimit = 5; 

                /*
                $startForm, configuración que contiene
                el numero de pagina que se desea mostrar
                */
                $startFrom = ($page-1)*$pageLimit;

                /**
                 * @var $id_usuario almacenara el id del usuario
                 */
                $id_usuario = $_SESSION['user']['id'];

                /**
                 * @var $dato almacenara el la información
                 * que el usuario desea buscar, esta se recibirá 
                 * mediante el método post
                 */
                $dato = $_POST['buscar'];

                /**
                 * Variable que almacena los datos posibles relacionados a la búsqueda.
                 * Se obtienen invocando la función search_record de recordModel.
                 * 
                 * @var array $results
                 */
                $results = $this->recordModel->search_record($id_usuario, $dato);

                /**
                 * Variable que almacena el número total de páginas en las que se debe paginar.
                 * Calcula el número total de filas y lo divide entre el límite de registros máximos a mostrar.
                 * 
                 * @var int $totalPages
                 */
                
                $totalPages = ceil(count($results)/$pageLimit);

                /**
                 * Almacena el código HTML necesario para la paginación.
                 * Este código se genera mediante la función utilitaria `paginator()`.
                 *
                 * @var string $pagination Contiene el HTML de la paginación.
                 */
                $pagination = paginator($page, $totalPages, '?c=record&a=list_record&p=');

                /**
                 * Variable que almacena los datos y que comienzan desde 
                 * cierto registro dado, es decir la paginación como tal
                 * 
                 * @var array $results
                 */
                $results = $this->recordModel->search_record_pagination($id_usuario, $dato, $startFrom, $pageLimit);
                
                // Carga de vista
                if ($results) {
                    $this->view('records', $results, $pagination);
                } elseif ($results == null) {
                    $this->view('records', 'Sin registros');
                } else {
                    $this->view('home');
                }
            }
        }
    }

    // Método seleccionador de vista
    public function view($view, $info = null, $pagination = null) {

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