<?php

namespace Model;

class Propiedad extends ActiveRecord{

    protected static $tabla = 'propiedades';
    protected static $columnasDB =['id','titulo','precio','imagen','descripcion','habitaciones','wc','estacionamiento','creado','vendedores_Id'];

    public $id;
    public $titulo;
    public $precio;
    public $imagen;
    public $descripcion;
    public $habitaciones;
    public $wc;
    public $estacionamiento;
    public $creado;
    public $vendedores_Id;

    public function __construct($args = [])
    {
        $this->id = $args['id']?? null;
        $this->titulo = $args['titulo']?? '';
        $this->precio = $args['precio']?? '';
        $this->imagen = $args['imagen']?? '';
        $this->descripcion = $args['descripcion']?? '';
        $this->habitaciones = $args['habitaciones']?? '';
        $this->wc = $args['wc']?? '';
        $this->estacionamiento = $args['estacionamiento']?? '';
        $this->creado = date('Y/m/d');
        $this->vendedores_Id = $args['vendedores_Id']?? '';
    }
    
        public function validar() {
    
            if(!$this->titulo) {
                self::$errores[] = "Debe tener un titulo";
            }
    
            if(!$this->precio) {
                self::$errores[] = "Debe tener un precio";
            }
    
            if( strlen($this->descripcion)< 50) {
                self::$errores[] = "Debe tener una descripcion y debe tener al menos 50 caracteres";
            }
            
            if(!$this->habitaciones) {
                self::$errores[] = "Debe tener al menos 1 habitacion";
            }
    
            if(!$this->wc) {
                self::$errores[] = "Debe tener un wc";
            }
    
            if(!$this->estacionamiento) {
                self::$errores[] = "El numero de estacionamientos es obligatorio";
            }
    
            if(!$this->vendedores_Id) {
                self::$errores[] = "Elige un vendedor";
            }
    
             if(!$this->imagen){
                 self::$errores[] = "Debe subir una imagen";
             }       
            return self::$errores;   
        }
}