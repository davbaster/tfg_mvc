<?php

require_once 'classes/session.php';
require_once 'models/usermodel.php';
//require_once 'models/pagosmodel.php';//DEBUG possiblement se necesite borrar //TODO borrar
//require_once 'models/peticionespagomodel.php';//DEBUG possiblement se necesite borrar  //TODO borrar

class SessionController extends Controller {

    private $userSession;
    private $username;
    private $userid;

    private $session;
    private $sites;
    private $defaultSites;

    private $user;

    function __construct(){

        // llamando al constructor padre
        parent::__construct();
        $this->init();
    }

    //getters
    public function getUserSession(){
        return $this->userSession;
    }

    public function getUsername(){
        return $this->username;
    }

    public function getUserId(){
        return $this->userid;
    }


    // crea una nueva session
    // le asigna un role a la sesion
    function init(){
        $this->session = new Session();

        $json = $this->getJSONFileConfig();

        // agrega los sitios
        $this->sites = $json['sites'];

        // agrega los sitios por defecto segun establecidos en archivo json
        $this->defaultSites = $json['default-sites'];

        $this->validateSession();
    }

    // extrae y formatea los roles de accesso del archivo json
    private function getJSONFileConfig(){
        $string = file_get_contents('config/access.json');
        $json = json_decode($string, true);

        return $json;
    }

    // verifica si usuario tiene session abierta hay session
    // verifica si la pagina que se quiere acceder es publica o privada
    // verifca si deacuerdo al rol del usuario tiene permiso para ver la pagina
    //init lo llama
    public function validateSession(){
        error_log('SESSIONCONTROLLER::validateSession');

        // si existe la session
        if($this->existsSession()){
            $role = $this->getUserSessionData()->getRol();

            //y la pagina a entrar es publica
            if($this->isPublic()){
                //Esto se puede quitar si siempre queremos que el usuario se tenga que loguear.
                // manda al usuario a su dashboard
                error_log( "SessionController::validateSession() => sitio p??blico, redirige al main de cada rol" );
                $this->redirectDefaultSiteByRole($role);
            }else{
                // si esta autorizado
                if($this->isAuthorized($role) ){
                    // lo dejo pasar
                    error_log( "SessionController::validateSession() => autorizado, lo deja pasar" );

                }else{
                    error_log( "SessionController::validateSession() => no autorizado, redirige al main de cada rol" );
                    // si no esta autorizado
                    // lo redirijo a su pagina default (El Dashboard)
                    $this->redirectDefaultSiteByRole($role);
                }
                
            }
        }else{
            // no existe la session
            //se valida si el accesso es publico o no
            if($this->isPublic() ){
                error_log('SessionController::validateSession() public page');
                // pagina publica
                // no pasa nada, lo deja entrar
            }else{
                // pagina  es privada
                // redirige al usuario al index (pagina login )
                error_log('SessionController::validateSession() redirect al login');
                header('Location: ' . constant('URL') . '/' . '');

            }
        }
    }


    // valida que ya hay una session abierta y que esta tiene informacion
    function existsSession(){

        // sino existe la session return false
        if(!$this->session->exists() ) return false;

        // verifica si la session creada tiene informacion
        if($this->session->getCurrentUser() == NULL) return false;

        // sino se activan las condicionales, entonces existe una session con informacion, y se hace las siguientes lineas
        $userid = $this->session->getCurrentUser();

        // si existe un usuario retorne verdadero
        if($userid) return true;

        //sino
        return false;
    }

    // deacuerdo a los datos (id = cedula) de la session crea un nuevo modelo de nuestro usuario
    // utilizando informacion de la BD
    function getUserSessionData(){
        
        $idCedula = $this->session->getCurrentUser();
        //error_log('SESSIONCONTROLLER::getUserSessionData -> La cedula que esta en la session es:' . $idCedula  );//Debugging line
        $this->user = new UserModel();
        // obtenemos los datos del usuario desde la BD
        $this->user->get($idCedula);
        error_log('SESSIONCONTROLLER::getUserSessionData -> ' . $this->user->getNombre());
        // retornamos el usuario con la informacion extraida desde la BD
        return $this->user;
    }


    // Lo utiliza Controller::login->authenticate
    function initialize($user){
    
        //error_log('SessionController:initialize -> cedula : '. $user->getCedula() );//DEBUGGING: Para revisar datos del objeto
        $this->session->setCurrentUser($user->getCedula());
        $this->authorizeAccess($user->getRol());
    }


    // verifica si la pagina es publica
    function isPublic(){

        error_log('SESSIONCONTROLLER::isPublic ');

        $currentURL = $this->getCurrentPage();
        // exp reg reemplaza ?.* por un string vacio
        $currentURL = preg_replace('/\?.*/', "", $currentURL); 

        // para cada sitio entonces
        //Recordar que cada form es visto como un sitio, aunque sea un popup form
        //entonces se le tiene que poner en el access.json con sus respectivos permisos
        for($i = 0; $i < sizeof($this->sites); $i++){

            // verifica si el url actual tiene nivel accesso publico
            if ($currentURL == $this->sites[$i]['site'] && 
                $this->sites[$i]['access'] == 'public') {
                
                return true;
            }
        }

        // sitio es privado
        return false;

    }

    // redirige al usuario a su pagina por defecto dependiendo del rol, si ya tiene una session abierta
    // Si yo siempre quiero que se tenga que loguear, creo que no habria que llamar el metodo
    //POSIBLE ERROR: si estoy en un sitio autorizado para mi rol, porque me va a dirigir al sitio por defecto para mi rol?
    //podria ir un if y un return empty para salir si estuviera autorizado
    private function redirectDefaultSiteByRole($role){
        $url = '';
        for($i = 0; $i < sizeof($this->sites); $i++){
            // a que pagina voy a redirigir deacuerdo a este rol
            if($this->sites[$i]['role'] == $role){
                //dado role user
                //            /www/dashboard
                $url = '/www/' . $this->sites[$i]['site'];
                //$url = '/' . $this->sites[$i]['site'];
                break;
            }
        }
        // voy a redirigir a
        error_log('SESSIONCONTROLLER::redirectDefaultSiteByRole -> ' . constant('URL') . $url );
        //header('location:' . constant('URL') . $url );//TODO si despues de terminada la app, se puede borrar esta linea
        header('location:' . $url );

    }


    // verifica si el usuario esta autorizado para ver esa pagina
    private function isAuthorized($role){

        $currentURL = $this->getCurrentPage();
        // exp reg reemplaza ?.* por un string vacio
        // quita los caracteres que no necesitamos, delimitador es ~
        $currentURL = preg_replace('/\?.*/', "", $currentURL); 


        // para cada sitio entonces
        for($i = 0; $i < sizeof($this->sites); $i++){

            // verifica si el rol actual esta autorizado para ver el site
            if ($currentURL == $this->sites[$i]['site'] && 
                $this->sites[$i]['role'] == $role) {
                
                return true;
            }
        }

        // usuario no autorizado
        return false;

    }


    //convierte el url en un arreglo y devuelve el string despues del http
    function getCurrentPage(){
        $actualLink = trim("$_SERVER[REQUEST_URI]");
        // divide el url en partes cada vez que se encuentra /
        $url = explode('/', $actualLink);
        error_log("sessionController::getCurrentPage(): actualLink =>" . $actualLink . ", url => " . $url[2]);

        // regresa despues del http
        return $url[2];
    }


    
    // devuelve al usuario a su pagina (dashboard) por defecto
    // dependiendo de su role
    function authorizeAccess($rol){

        error_log('SessionController:authorizeAccess -> rol : '. $rol );//DEBUGGING: Para revisar datos del objeto

        switch ($rol) {
            case 'contratista':
                $this->redirect($this->defaultSites['contratista'], []);
                break;

            case 'administrador':
                $this->redirect($this->defaultSites['administrador'], []);

            case 'supervisor':
                $this->redirect($this->defaultSites['administrador'], []);

                
                break;
            default:
            
        }
    }

    // sale de la session
    function logout(){
        $this->session->closeSession();

    }

}

?>