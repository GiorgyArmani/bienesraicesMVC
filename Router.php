<?php

namespace MVC;


class Router {

    public $rutasGET = [];

    public $rutasPOST = [];

    public function get($url, $fn){
        $this->rutasGET[$url] = $fn;
    }
    public function post($url, $fn){
        $this->rutasPOST[$url] = $fn;
    }

    public function comprobarRutas()
    {
        session_start();

        $auth = $_SESSION['login'] ?? null;

        //arreglo de rutas protegidas
        $rutas_protegidas = ['/admin', '/propiedades/crear', '/propiedades/actualizar', '/propiedades/eliminar','/vendedores/crear','/vendedores/actualizar',
        '/vendedores/eliminar',];

        $urlActual = $_SERVER['PATH_INFO'] ?? '/';
        $metodo = $_SERVER['REQUEST_METHOD'];

        if($metodo === 'GET'){
            
            $fn = $this->rutasGET[$urlActual] ?? null;
        } else{
            
            $fn = $this->rutasPOST[$urlActual] ?? null;
        }   
        //proteger las rutas 
        if(in_array($urlActual, $rutas_protegidas) && !$auth){
            header('Location: /');
        }



        if($fn){
            call_user_func($fn, $this);
        }else{
            echo 'No existe la ruta';
        }
    }
    //muestra una vista

    public function render($view, $datos = []){

        foreach($datos as $key => $value){
            $$key = $value; // significa $$ variable de variable
        }

        //inicia almacenamiento en memoria
        ob_start();
        //vista dinamica
        include __DIR__ . "/views/$view.php";
        //limpiamos la memoria
        $contenido = ob_get_clean();
        //vista a la master page
        include __DIR__ . "/views/layout.php";
    }
}