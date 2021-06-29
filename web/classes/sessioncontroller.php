<?php

class SessionController extends Controller {

    private $userSession;
    private $username;
    private $userid;

    private $session;
    private $site;

    private $user;

    function __construct(){

        // llamando al constructor padre
        parent::__construct();
        $this->init();
    }

    // crea una nueva session
    // le asigna un role a la sesion
    function init(){
        $this->session = new Session();

        $json = $this->getJSONFileConfig();

        $this->sites = $json['sites'];
        // agrega los sitios por defecto segun establecidos en archivo json
        $this->defaultSites = $json['defaultsites'];

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
    public function validateSession(){
        error_log('SESSIONCONTROLLER::validateSession');

        // si existe la session
        if($this->existsSession()){
            $role = $this->getUserSessionData()->getRole();

            // valida si la pagina a entrar es publica
            if($this->isPublic()){

            }
        }else{
            // no existe la session
        }
    }


    // valida que ya hay una session abierta y que esta tiene informacion
    function existsSession(){

        // sino existe la session return false
        if(!$this->session->exists() ) return false;

        // verifica si la session creada tiene informacion
        if($this->session->get_current_user() == NULL) return false;

        // sino se activan las condicionales, entonces existe una session con informacion, y se hace las siguientes lineas
        $userid = $this->session->get_current_user();

        // si existe un usuario retorne verdadero
        if($userid) return true;

        //sino
        return false;
    }

    // deacuerdo a los datos de la session crea un nuevo modelo de nuestro usuario
    // utilizando informacion de la BD
    function getUserSessionData(){

        $cedula = $this->userid;
        $this->user = new UserModel();
        // obtenemos los datos del usuario desde la BD
        $this->user->get($cedula);
        error_log('SESSIONCONTROLLER::getUserSessionData -> ' . $this->user->getName());
        // retornamos el usuario con la informacion extraida desde la BD
        return $this->user;
    }

    // verifica si la pagina es publica
    function isPublic(){
        $currentURL = $this->getCurrentPage();
        // exp reg reemplaza ?.* por un string vacio
        $currentURL = preg_replace("/\?.*/", "", $currentURL);

    }

    //convierte el url en un arreglo y devuelve el string despues del http
    function getCurrentPage(){
        $actualLink = trim("$_SERVER[REQUEST_URI]");
        // divide el url en partes cada vez que se encuentra /
        $url = explode('/', $actualLink);
        error_log('SESSIONCONTROLLER::getCurrentPage -> ' . $url[2]);

        // regresa despues del http
        return $url[2];
    }

}

?>