<?php
    class App{

        function _construct(){
            $url = isset($_GET['url']) ? $_GET['url'] : null;
            // quita los / del url
            $url = rtrim($url, '/');
            // divide en partes el url y los guarda en array
            $url = explode('/', $url);
            //usuario/hacerPago

            //verifica si no se define un nombre de controlador
            if(empty($url[0])){
                error_log('APP::CONSTRUCT-> no hay controlador especificado. ');
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

            $archivoController = 'controllers/' . url[0] . '.php';

            
            if(file_exists($archivoController)){
                // existe nombre controlador, y lo mandamos a llamar
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
                    }

                }else{
                    // no hay metodo a cargar, se carga el metodo por default
                    $controller->render();

                }


            }else{
                // no existe archivo, despliega error
            }


        }

    }

?>