<?php

namespace Controllers;

use Model\Vendedor;
use MVC\Router;

class VendedorController {
    public static function crear(Router $router) {
        $errores = Vendedor::getErrores();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $vendedor = new Vendedor($_POST['vendedor']);
            $errores = $vendedor->validar();

            if (empty($errores)) {
                $vendedor->save();
            }
        }

        $router->render('vendedores/crear', [
            'errores' => $errores,
            'vendedor' => $vendedor
        ]);
    }

    public static function actualizar(Router $router) {
        $id = validarORedireccionar('/admin');
        /* Validar y obtener el id */
        $vendedor = Vendedor::find($id);
        $errores = Vendedor::getErrores();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //asignar los valores
            $args = $_POST['vendedor'];
            $vendedor->sincronizar($args);

            //validacion
            $errores = $vendedor->validar();

            if (empty($errores)) {
                $vendedor->save();
            }
        }

        $router->render('vendedores/actualizar', [
            'errores' => $errores,
            'vendedor' => $vendedor
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
                    $vendedor = Vendedor::find($id);
                
                    $vendedor->delete();
                  
                 }
                }
            }
        } 
    }
