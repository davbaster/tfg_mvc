<?php

    require_once 'controllers/errores.php';

    class App{

        function __construct(){
            $url = isset($_GET['url']) ? $_GET['url'] : null;
            // quita los / al final de la url 
            $url = rtrim($url, '/');
            // divide en partes el url y los guarda en array
            //http://localhost/www/usuario/hacerPago
            $url = explode('/', $url); 
            ///parametro url contiene = usuario/hacerPago



            //verifica si no se define un nombre de controlador
            if(empty($url[0])){
                error_log('APP::CONSTRUCT-> No hay controlador especificado. ');

                // si no existe controlador
                $archivoController = 'controllers/login.php';
                require_once $archivoController;
                $controller = new Login();
                // carga modelo
                $controller->loadModel('login');
                // renderiza vista
                $controller->render();
                return false;
            }

            //Si en el $url viene el nombre de un controlador
            $archivoController = 'controllers/' . $url[0] . '.php';
            error_log('APP::CONSTRUCT-> controlador especificado es: ' . $url[0] . '.php');
            
            if(file_exists($archivoController)){
                // existe el archivo del controlador, entonces lo incluimos
                require_once $archivoController;

                $controller = new $url[0]; //$url[0] hace referencia al nombre del controlador
                $controller->loadModel($url[0]); 

                //revisa si hay segundo parametro
                // si hay un metodo en el url[1] /controllador/metodo1
                if(isset($url[1])){
                    
                    // valida que dentro de este objeto($controller) exista el metodo1 ($url[1])
                    if(method_exists($controller, $url[1])){
                        
                        // verifica si hay parametros para el metodo1
                        if(isset($url[2])){
                            

                            // numero parametros
                            //quito los dos primeros parametros
                            $nparam = count($url) - 2;//0=nombreControlador, 1=metodo

                            // arreglo de parametros
                            $nparams = [];

                            for($i = 0; $i < $nparam; $i++){
                                // +2 para brincarse el nombre del controlador, y el metodo
                                array_push($nparams, $url[$i] + 2);
                            }
                            //mandando a llamar el metodo enviando parametros
                            $controller->{$url[1]}($nparams);  
                        }else{
                            // no tiene parametros, se manda a llamar
                            // corre el metodo tal cual
                            $controller->{$url[1]}();
                        }

                    }else{
                        // error, no existe el metodo
                        $controller = new Errores();
                        $controller->render();
                    }

                }else{
                    // no hay metodo a cargar, se carga el metodo por default
                    $controller->render();

                }


            }else{
                // no existe archivo, despliega error
                //metodo por defecto
                $controller = new Errores();
                $controller->render();
            }


        }

    }

?>