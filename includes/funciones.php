<?php


define('TEMPLATES_URL',__DIR__.'/templates');
define('FUNCIONES_URL',__DIR__.'funciones.php');
define('CARPETA_IMAGENES', $_SERVER['DOCUMENT_ROOT']. '/imagenes/');

function incluirtemplate( string $nombre, bool $inicio = false) {
    include TEMPLATES_URL."/{$nombre}.php";
}

function estaAutenticado() {
    session_start();

    if (!$_SESSION['login']) 
    {
        header('Location: /');
    }
    
}

function debuguear($variable)
{
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

//escapa /sanitiza  el html  super importante 

function s($html) : string{
    $s = htmlspecialchars($html);
    return $s;
}

//validar tipo de contenido
function validateContentType($tipo){
    $tipos = ['vendedor', 'propiedad'];

    return in_array($tipo, $tipos);
}

//muestra los mensajes

function mostrarNotificacion($codigo){
    $mensaje = '';

    switch($codigo) {
        case 1:
            $mensaje = 'Creado Exitosamente';
            break;
        case 2:
            $mensaje = 'Actualizado Exitosamente';
            break;
        case 3:
            $mensaje = 'Eliminado Exitosamente';
            break;

        default:
        $mensaje= false;
        break;
    }
    return $mensaje;
}

function validarORedireccionar(string $url){
     // validar la URL por ID valido
     $id = $_GET['id'];
     $id = filter_var($id, FILTER_VALIDATE_INT);
 
     if(!$id){
         header("Location:  $url");
     }
     return $id;
}