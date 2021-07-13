<?php
    

    class Controller{

        // private $model;

        function __construct(){
            $this->view = new View();
        }
    
        //app.php construct calls this function
        function loadModel($model){
            $url = 'models/' . $model . 'model.php';
            error_log('CONTROLLER::loadModel =>Mostrando valor de models/$model: ' . $url); //Debugging: revisando si se crea el objeto $this->model

            if(file_exists($url)){
                require_once $url;

                // armando nombre del model camelCase
                $modelCap = ucfirst($model); //primera letra en mayuscula
                $modelName = $modelCap.'Model';
                //$modelName = $model.'Model';
                error_log('CONTROLLER::loadModel =>Mostrando valor de $modelName: ' . $modelName); //Debugging: *arreglado* la primera letra de la url no esta en mayuscula

                $this->model = new $modelName(); 
                $this->model->holaMundo(); //Debugging: solo existe para ver si el objeto es creado, y la funcion es especifica del objeto LoginModel.                                                
            }
        }

        // verifica si existe cuando reciba parametros 
        // con que un parametro no existe, devuelve false
        function existPOST($params){
            foreach($params as $param){
                if(!isset($_POST[$param])){
                    error_log('CONTROLLER::existsPost => No existe el parametro ' . $param);
                    return false;
                }
            }
            return true; //existen todos los parametros
        }

        // verifica si existe cuando reciba parametros 
        // con que un parametro no existe, devuelve false
        function existGET($params){
            foreach($params as $param){
                if(!isset($_GET[$param])){
                    error_log('CONTROLLER::existsGet => No existe el parametro ' . $param);
                    return false;
                }
            }
            return true; //existen todos los parametros
        }

        //ahorra tiempo para no utilizar sintaxis $_GET[$name]
        function getGet($name){
            return $_GET[$name];
        }

        //ahorra tiempo para no utilizar sintaxis $_POST[$name]
        function getPost($name){
            return $_POST[$name];
        }

        // redirige al usuario a una pagina
        function redirect($route, $mensajes){


            // error_log('CONTROLLER::redirect => ruta= ' . $route);

            $data = [];
            $params = '';

            foreach($mensajes as $key => $mensaje){
                //                clave        valor   
                array_push($data, $key . '=' . $mensaje);
            }
            // uned los elementos de un arreglo con un caracter &, permite delimitar cada uno de los parametros
            $params = join('&', $data);

            // ?nombre=David&apellido=Cordoba
            if($params != ''){
                $params = '?' . $params;
            }
            // redirige a la pagina principal, ver constantes en config.php
            header('Location: ' . constant("URL") . '/' . $route . $params);
        }


    }

?>