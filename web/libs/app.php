<?php

    require_once 'controllers/errores.php';

    class App{

        function __construct(){
            $url = isset($_GET['url']) ? $_GET['url'] : null;
            // quita los / del url
            $url = rtrim($url, '/');
            // divide en partes el url y los guarda en array
            $url = explode('/', $url);
            //usuario/hacerPago

            error_log('APP::CONSTRUCT-> contenido de $url: ' . $url[0]); //DEBUGGING: revisando el contenido

            //verifica si no se define un nombre de controlador
            if(empty($url[0])){
                error_log('APP::CONSTRUCT-> no hay controlador especificado, cargando controllador por defecto login.php');
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

                $controller = new $url[0];
                $controller->loadModel($url[0]);

                // si hay un metodo en el url /controllador/metodo1
                if(isset($url[1])){
                    
                    // valida que dentro de este objeto($controller) exista el metodo1 ($url[1])
                    if(method_exists($controller, $url[1])){
                        
                        // verifica si hay parametros para el metodo
                        if(isset($url[2])){
                            

                            // numero parametros
                            $nparam = count($url) - 2;

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
                            // el metodo tal cual
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
                $controller = new Errores();
                $controller->render();
            }


        }

    }

?>