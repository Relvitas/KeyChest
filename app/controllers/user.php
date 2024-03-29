<?php

class UserController {
    private UserModel $userModel;
    private TokenModel $tokenModel;
    private EmailSender $emailSender;

    public function __construct() {
        //llamada modelo 'UserModel'
        require_once 'models/user.php';
        //llamada modelo 'TokenModel'
        require_once 'models/token.php';
        //llamada de utilidad phpmailer
        require_once 'utils/email_sender.php';

        //instancias de 'UserModel'
        $this->userModel = new UserModel(
            // dependencia con conexión db
            Connection::connection()
        );

        //instancia 'TokenModel'
        $this->tokenModel = new TokenModel(
            // dependencia con conexión db
            Connection::connection()
        );

        //instancia utilidad 'email_sender'
        $this->emailSender = new EmailSender();
    }

    public function index() {
        $this->view('sign_in');
    }

    //método iniciar sesión
    public function sign_in() {
        if (isset($_POST['sign_in'])) {
            //validar datos post no estén vacíos y almacenar
            $email = empty($_POST['email']) 
                ? null 
                : $_POST['email'];

            $password = empty($_POST['password'])
                ? null 
                : $_POST['password'];
            
            //validar que post sea diferente de null
            if ($email != null && $password != null) {
                //llamada a la función 'validate_email_user' para obtener id y validar email
                if ($userData = $this->userModel->validate_email_user($email)) {
                    //validar inicio sesión 'modelUser>validate_sign_in'
                    if ($this->userModel->validate_sign_in($email, $password, $userData->id)) {
                        //creamos sesión
                        session_start();
                        //almacenamos datos de sesión
                        $_SESSION['user']['id'] = $userData->id;
                        $_SESSION['user']['name'] = $userData->nombre;
                        //cargar vista inicio
                        $this->view('home');
                    } else {
                        //contraseña incorrecta
                        $this->view('sign_in', 'Clave incorrecta');
                    }
                } else {
                    //correo incorrecto
                    $this->view('sign_in', 'Correo inexistente');
                }
            } else {
                //datos incompletos
                $this->view('sign_in', 'Datos incompletos');
            }
        } else {
            //acceso incorrecto
            $this->view('sign_in', 'Acceso incorrecto');
        }
    }

    //método registrar usuario
    public function sign_up(){
        //validar si se presiona botón enviar
        if (isset($_POST['sign_up'])) {

            //validar que 'username' este definido y no vació
            if (isset($_POST['username']) && !empty($_POST['username'])) { 
                $name = $_POST['username'];

                //validar que 'email' este definido y no vacio
                if (isset($_POST['email']) && !empty($_POST['email'])) {
                    $email = $_POST['email'];

                    //validar que 'password' este definido y no vacio
                    if (isset($_POST['password']) && !empty($_POST['password'])) {
                        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    } else {
                        $this->view('sign_up', 'Campo incompleto');
                    }
                } else {
                    $this->view('sign_up', 'Campo incompleto');
                }
            } else {
                $this->view('sign_up', 'Campo incompleto');
            }

            //llamada de función de insertar datos de un nuevo registro usuario
            $insertion_results = $this->userModel->sign_up($name, $email, $password);
            
            //validar resultados de inserción
            if ($insertion_results === 'falla') {
                //redirigir con error de falla en consulta
                $this->view('sign_up', 'Falla Sistema'); 
            } else if ($insertion_results === 'éxito') {
                //redirigir con éxito de inserción
                $this->view('sign_in', 'Éxito');
            } else {
                //redirigir con alerta de correo existente
                $this->view('sign_up', 'Correo existente');
            }
        }
    }

    //método solicitud restablecer contraseña
    public function reset_password() {
        //validar si se pulsa botón
        if (isset($_POST['reset_password'])) {

            //validar que la variables estén definidas y tengan datos
            if (isset($_POST['email']) && !empty($_POST['email'])) {
                //almacenar post[email] en variable
                $email = $_POST['email'];

                //validar email
                $data = $this->userModel->validate_email_user($email);
                if ($data) {
                    //almacenar id de usuario
                    $idUser = $data->id;
                    
                    //establecer token usuario y validar resultado
                    if ($this->tokenModel->save_new_token($idUser)) {

                        //obtener token de usuario
                        $token = $this->tokenModel->search_token($idUser);
                        $token = $token->token;

                        //enviar email con enlace
                        $message = '
                            Tiene 5 horas para restablecer su contraseña<br>
                            <a href="http://localhost/php/key_chest/app/?id=' . $idUser . '&a=new_password&t=' . urlencode($token) . '">restablecer clave</a>
                            ';
        
                        //llamada de método 'send_email' enviar correo
                        if ($this->emailSender->send_email($email, $message)) {
                            //correo enviado
                            $this->view('reset_password', 'Correo enviado');
                        } else {
                            //falla sistema de correo
                            $this->view('reset_password', 'Falla sistema');
                        }
                    } else {
                        //falla en db
                        $this->view('reset_password', 'Falla sistema');
                    }
                } else {
                    //correo no existente
                    $this->view('reset_password', 'Correo invalido');
                }
            } else {
                //llama vista 'reset_password' y error de campo no rellenado
                $this->view('reset_password', 'Campo incompleto');
            }
        } else {
            //llama vista 'reset_password' y error por no presionar botón
            $this->view('reset_password', 'Acceso incorrecto');
        }
    }

    public function new_password(){
        // validar si variable esta declarada y tiene información
        if ((isset($_GET['t']) && !empty($_GET['t']) && (isset($_GET['id']) && !empty($_GET['id'])))) {
            //almacenar datos GET
            $token = $_GET['t'];
            $idUser = $_GET['id'];
            $data = [
                'token' => $token,
                'idUser' => $idUser
            ];

            //establecer vista y enviar dataArray
            $this->view('new_password', $data);
        } else {
            $this->view('reset_password', 'Datos incompletos');
        }   
    }

    public function set_password(){
        // validar si se presiono botón enviar
        if (isset($_POST['set_password'])) {
            
            //validar envió POST
            if (
                (isset($_POST['password']) && !empty($_POST['password'])) &&
                (isset($_POST['confirm_password']) && !empty($_POST['confirm_password'])) &&
                (isset($_POST['idUser']) && !empty($_POST['idUser'])) &&
                (isset($_POST['token']) && !empty($_POST['token']))
            ) {

                //almacenar datos POST
                $password = $_POST['password'];
                $confirm_password = $_POST['confirm_password'];
                $token = $_POST['token'];
                $idUser = $_POST['idUser'];
                $data = [
                    'token' => $token,
                    'idUser' => $idUser
                ];

                //validación de token
                $info = $this->tokenModel->validate_token($idUser, $token);
                //si validation_token da OK
                if ($info === 'proceder_cambio') {

                    //confirmar contraseña
                    if ($password === $confirm_password) {

                        //actualizar contraseña, método 'new_password' modelo usuario
                        if ($this->userModel->new_password($idUser, $password)) {
                            //redirigir a inicio sesión en caso de éxito de restablecimiento
                            $this->view('sign_in', 'Éxito');
                        } else {
                            //redirigir 'sign_in' en caso de error del sistema
                            $this->view('sign_in', 'Falla sistema');
                        }
                    } else {
                        //llamar vista 'new_password' y adjuntar nuevamente token y idUsuario
                        return $this->view('new_password', $data);
                    }
                } else {
                    //llamar vista 'reset_password' y envía resultados
                    $this->view('reset_password', $info);
                }
            } else {
                //llamar vista restablecer_contraseña
                $this->view('reset_password');
            }
        } else {
            $this->view('reset_password');
        }
    }

    public function sign_out() {
        session_start();
        session_destroy();
        $this->view('sign_in');
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