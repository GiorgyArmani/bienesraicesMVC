<?php

namespace Model;

class ActiveRecord {
        // base de datos
        protected static $db;
        //arreglo que da forma a los datos que van al objeto de php columnas y nos ayuda a mapear el objeto a crear 
        
        protected static $tabla = '';
        
        protected static $columnasDB =[];
        
        protected static $errores = [];
         // Definir la conexión a la BD
        public static function setDB($database){
                self::$db = $database;
        }
        //validacion de datos
        public static function getErrores() {
            return static::$errores;
        }
        public function validar() {
            static::$errores = [];
            return static::$errores;   
        }  
        // Registros - CRUD
        public function save(){
            $resultado = '';
                   
            if(!is_null($this->id)) {
                //actualizar 
                $resultado = $this->update();
            } else{
                //creando nuevo registro
                $resultado = $this->create();
            }
            return $resultado;
        }  
        //lista todos los registros
    public static function all(){
                $query = "SELECT * FROM " . static::$tabla;

                $resultado = self::consultarSQL($query);

                return $resultado;
        } 

     //obtiene determinado numero de registro
     public static function get($cantidad){
        $query = "SELECT * FROM " . static::$tabla . " LIMIT " . $cantidad;

        $resultado = self::consultarSQL($query);

        return $resultado;
}
        // busca un registro por su id
    public static function find($id){
                $query = "SELECT * FROM " . static::$tabla . " WHERE id = $id";

                $resultado = self::consultarSQL($query);

                return array_shift($resultado);
        }
    public function create(){
                //sanitize the data
                $data = $this->sanitizeData();
       
                
                    //insert the data on the database
                    $query = " INSERT INTO ". static::$tabla . " ( ";
                    $query.= join (', ', array_keys($data));
                    $query.= " ) VALUES (' "; 
                    $query.= join ("', '", array_values($data));
                    $query.= " ')";
            
                    $resultado = self::$db->query($query);

                    if($resultado){  
                        // redirect to the admin page with a success message
                        header('Location: /admin?resultado=1');
                    }    
            }

    public function update(){

                $data = $this->sanitizeData();
        
                $valores = [];
                foreach ($data as $key => $value) {
                    $valores[] = "{$key} = '{$value}'";
                }
                $query = "UPDATE " . static::$tabla . " SET ";
                $query .=  join(',', $valores );
                $query .= " WHERE   id = '". self::$db->escape_string($this->id) ."' ";
                $query .= " LIMIT 1";
                
                $resultado = self::$db->query($query);
        
                if($resultado){  // redirect to the admin page with a success message
                    header('Location: /admin?resultado=2');
                }
            }
        
        public function delete(){
                  // Eliminar la propiedad
                  $query = "DELETE FROM " . static::$tabla .  " WHERE id = " . self::$db->escape_string($this->id) . " LIMIT 1";
                  $resultado = self::$db->query($query);
        
                  if($resultado){
                    $this->deleteImagen();
                    header('Location: /admin?resultado=3');
                  }
                
            }       
        public static function consultarSQL($query){
                //consultar la base de datos
                $resultado= self::$db->query($query);
                
                //iterar los resultados
                $array = [];
                while($registro = $resultado->fetch_assoc()){
                    $array[] = static::crearObjeto($registro);
        
                }
                //liberar la memoria de la base de datos
                $resultado->free();
        
                //retornar resultados
                return $array;
            } 
            
        protected static function crearObjeto($registro){
                $objeto = new static;
        
                foreach($registro as $key => $value){
                   if(property_exists( $objeto, $key )){
                        $objeto->$key = $value;
                   }
                }
                return $objeto;
            }
            //identificar y unir los atributos de la base de datos
        public function data(){
                $data = [];
                foreach(static::$columnasDB as $columna){
                    if($columna ==='id') continue;
                    $data[$columna] = $this->$columna;
                }
                return $data;
            }
        
        public function sanitizeData(){
                $data = $this->data();
                $sanitizado = [];
        
                foreach($data as $key => $value){
                    $sanitizado[$key] = self::$db->escape_string($value);
                }
                return $sanitizado;
            }
        
            //subida de archivos
        public function setImagen($imagen){
                //elimina la imagen previa
                if( !is_null($this->id) ){
                   $this->deleteImagen();
                }
        
                if($imagen){
                //asignar el atributo de imagen el nombre de la imagen
                    $this->imagen = $imagen;
                }
            }
            //elimina archivo
        public function deleteImagen(){
                    //comprobar si existe el archivo
                    $existeArchivo = file_exists(CARPETA_IMAGENES. $this->imagen); 
                    if($existeArchivo){
                        unlink(CARPETA_IMAGENES. $this->imagen);
                    }
                }
     //sincroniza el objeto en memoria con los cambios realizados en la web por user
        public function sincronizar($args = []){
                foreach($args as $key => $value){
                    if(property_exists($this, $key) && !is_null($value)){
                        $this->$key = $value;
                    }
             }
            }
    }

