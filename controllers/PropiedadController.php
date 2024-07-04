<?php

namespace Controllers;
use MVC\Router;
use Model\Propiedad;
use Model\Vendedor;
use Intervention\Image\ImageManager as Image;
use Intervention\Image\Drivers\Gd\Driver;   
    

class PropiedadController{
    public static function index(Router $router) {
        
        $propiedades = Propiedad::all();
        $vendedores = Vendedor::all();
        $resultado = $_GET['resultado']?? null;
        
        $router->render('propiedades/admin', [
            'propiedades' => $propiedades,
            'resultado' => $resultado,
            'vendedores' => $vendedores
        ]);
    }

    public static function crear(Router $router) {

        $propiedad = new Propiedad();
        $vendedores = Vendedor::all();
        $errores = Propiedad::getErrores();

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
        
            /* Crea una nueva instancia */
            $propiedad = new Propiedad($_POST['propiedad']);
            
            /** Subida de archivos **/
            //Generar un nombre único
            $nombreImagen = md5( uniqid( rand(), true) ) . ".jpg";
     
            /*Setear la imagen*/
            //Realiza un resize a la imagen con intervention
            if ($_FILES['propiedad']['tmp_name']['imagen']) {
                $manager = new Image(Driver::class);
                $image = $manager->read($_FILES['propiedad']['tmp_name']['imagen'])->cover(800,600);
                $propiedad->setImagen($nombreImagen);
            }
     
            /*Validar */
            $errores = $propiedad->validar();
            //Revisar que el arreglo de errores esté vacío
            if (empty($errores)) {
                //Crear una carpeta
                if (!is_dir(CARPETA_IMAGENES)) {
                    mkdir(CARPETA_IMAGENES);
                }
                //Dar permisos a la carpeta y Guarda la imagen en el servidor
                chmod(CARPETA_IMAGENES, 0777);
                $image->save(CARPETA_IMAGENES . $nombreImagen);
               //Guarda en la base de datos
                $propiedad->save(); 
            }   
        }

        $router->render('propiedades/crear', [
            'propiedad' => $propiedad,
            'vendedores' => $vendedores,
            'errores' => $errores
        ]);
    }

    public static function actualizar(Router $router)
    {
 
        /* Validar y obtener el id */
        $id = validarORedireccionar('/admin');
 
        $propiedad = Propiedad::find($id);
        $vendedores = Vendedor::all();
        $errores = Propiedad::getErrores();
 
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 
            //asignar los atributos
            $args = $_POST['propiedad'];
            $propiedad->sincronizar($args);
            //validacion
            $errores = $propiedad->validar();
            if (empty($errores)) {
                if ($_FILES['propiedad']['tmp_name']['imagen']) {
                    //Generar un nombre único
                    $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";
                    //subida de archivos
                    $manager = new Image(Driver::class);
                    $image = $manager->read($_FILES['propiedad']['tmp_name']['imagen'])->cover(800, 600);
                    $propiedad->setImagen($nombreImagen);
 
                    if (isset($image)) {
                        $image->save(CARPETA_IMAGENES . $nombreImagen);
                    }
                }
 
                $resultado = $propiedad->save();
                if ($resultado) {
                    header('location: /admin?resultado=2');
                }
            }
        }
 
        $router->render('propiedades/actualizar', [
            'propiedad' => $propiedad,
            'errores' => $errores,
            'vendedores' => $vendedores
        ]);
    }

    public static function eliminar() {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        //validar id
        $id = $_POST['id'];
        $id = filter_var($id, FILTER_VALIDATE_INT);

        if ($id) {
            $tipo = $_POST['tipo'];

            if(validateContentType($tipo)){
                $propiedad = Propiedad::find($id);
            
                $propiedad->delete();
              
             }
            }
        }
    }   
}

     
