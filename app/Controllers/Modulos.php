<?php
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\ModulosModel;
use App\Models\RegistrosModel;

class Modulos extends Controller{
    public function index(){
        $request = \Config\Services::request(); 
        $validation = \Config\Services::validation();
        $headers = $request->getHeaders();
        $model = new RegistrosModel();
        $registro=$model->where('estado', 1)
        ->findAll();
        foreach($registro as $key => $value){
            if(array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])){
                if($request->getHeader('Authorization')=='Authorization: Basic '.base64_encode($value["cliente_id"].":".$value["llave_secreta"])){
                            
                    $model = new ModulosModel();
                    $modulos = $model->where('estado', 1)
                    ->findAll();
                    if (empty($modulos)) {
                        $data = array(
                            "Status"=>404,
                            "Total de resultados" => 0,
                            "Detalles"=>"No tenemos registros de productos en la base de datos"
                        );
                    }
                    
                    else{
                        $data = array(
                            "Status" => 200,
                            'Total de resultados' => count($modulos),
                            "Detalles" => $modulos
                        );
                        return json_encode($data, true);
                    }   
                                    
                }
                else{
                    $data = array(
                        "Status"=>404,
                        "Detalles"=>"El token es inválido"
                    );                              
                }
            }
            else
            {
                $data = array( "Status"=>404, "Detalles"=>"No está autorizado para guardar registros");     
            }
        }
        return json_encode($data, true);
    }

    public function show ( $id ){
        $request = \Config\Services::request(); 
        $validation = \Config\Services::validation();
        $headers = $request->getHeaders();
        $model = new RegistrosModel();
        $registro=$model->where('estado', 1)
        ->findAll();
        foreach($registro as $key => $value){
            if(array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])){
                if($request->getHeader('Authorization')=='Authorization: Basic '.base64_encode($value["cliente_id"].":".$value["llave_secreta"])){
                            
                    $model = new ModulosModel();
                    $modulos = $model->where('estado', 1)
                    ->find( $id );
                    if (empty($modulos)) {
                        $data = array(
                            "Status"=>404,
                            "Detalles"=>"No hay ningún modulos con este id" 
                        );
                    }
                    else{
                        $data = array(
                            'Status' => 200,
                            "Detalles" => $modulos
                        );
                        return json_encode($data, true);
                        
                    }   
                                    
                }
                else{
                    $data = array(
                        "Status"=>404,
                        "Detalles"=>"El token es inválido"
                    );                              
                }
            }
            else
            {
                $data = array( "Status"=>404, "Detalles"=>"No está autorizado para guardar registros");     
            }
        }
        return json_encode($data, true);
    }
    public function create(){
        $request = \Config\Services::request(); 
        $validation = \Config\Services::validation();
        $headers = $request->getHeaders();
        $model = new RegistrosModel();
        $registro=$model->where('estado', 1)
        ->findAll();
        foreach($registro as $key => $value){
            if(array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])){
                if($request->getHeader('Authorization')=='Authorization: Basic '.base64_encode($value["cliente_id"].":".$value["llave_secreta"])){
                    // Toma de datos del POSTMAN        
                    $datos = array(
                        "nombre"=>$request->getVar("nombre"),
                        "icono"=>$request->getVar("icono"),
                        "url"=>$request->getVar("url"),
                        "padre"=>$request->getVar("padre"),
                        "orden"=>$request->getVar("orden")
                    );
                    if(!empty($datos)){
                        // Validar los datos
                        $validation->setRules([
                            'nombre' => 'required|string|max_length[255]',
                            'icono' => 'required|string|max_length[255]',
                            'url' => 'required|string|max_length[255]',
                            'padre' => 'required|string|max_length[255]',
                            'orden' => 'required|string|max_length[255]'
                        ]);
                        $validation->withRequest($this->request)
                        ->run();    
                        if($validation->getErrors()){
                            $errors = $validation->getErrors();
                            $data = array("Status"=>404, "Detalle"=>$errors);                           
                            return json_encode($data, true); 
                        }
                        else{
                            $datos = array(
                                "modulo_nombre"=>$datos["nombre"],
                                "modulo_icono"=>$datos["icono"],
                                "modulo_url"=>$datos["url"],
                                "modulo_padre"=>$datos["padre"],
                                "modulo_orden"=>$datos["orden"]
                            );          
                            $model = new ModulosModel();
                            $modulos = $model->insert($datos);
                            $data = array(
                                "Status"=>200,
                                "Detalle"=>"Registro exitoso, datos de modulos guardado"
                            );              
                            return json_encode($data, true);
                        }

                    }
                    else{
                        $data = array("Status"=>404, "Detalle"=>"Registro con errores");
                        return json_encode($data, true);
                    }               
                }
                else{
                    $data = array("Status"=>404, "Detalles"=>"El token es inválido");                               
                }
            }
            else
            {
                $data = array( "Status"=>404, "Detalles"=>"No está autorizado para guardar registros");     
            }
        }
        return json_encode($data, true);
    }
    
    public function update( $id ){
        $request = \Config\Services::request();
        $validation = \Config\Services::validation();
        $headers = $request->getHeaders();
        $model = new RegistrosModel();
        $registro=$model->where('estado', 1)
        ->findAll();
        foreach($registro as $key => $value){
            if(array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])){
                if($request->getHeader('Authorization')=='Authorization: Basic '.base64_encode($value["cliente_id"].":".$value["llave_secreta"])){      
                    
                    $datos = $this->request->getRawInput();
                    
                    if(!empty($datos)){
                        // Validar los datos
                        $validation->setRules([
                          'nombre' => 'required|string|max_length[255]',
                            'icono' => 'required|string|max_length[255]',
                            'url' => 'required|string|max_length[255]',
                            'padre' => 'required|string|max_length[255]',
                            'orden' => 'required|string|max_length[255]'
                        ]);
                        $validation->withRequest($this->request)
                        ->run();    
                        if($validation->getErrors()){
                            $errors = $validation->getErrors();
                            $data = array("Status"=>404, "Detalle"=>$errors);                           
                            return json_encode($data, true); 
                        }
                        else{
                            $model = new ModulosModel();
                            $modulos = $model->find($id);
                            $datos = array(
                                "modulo_nombre"=>$datos["nombre"],
                                "modulo_icono"=>$datos["icono"],
                                "modulo_url"=>$datos["url"],
                                "modulo_padre"=>$datos["padre"],
                                "modulo_orden"=>$datos["orden"]
                            );           
                            $modulos = $model->update($id, $datos);
                            $data = array(
                                "Status"=>200,
                                "Detalle"=>"Actualización exitosa, datos de modulos modificado"
                            );              
                            return json_encode($data, true);
                        }

                    }
                    else{
                        $data = array("Status"=>404, "Detalle"=>"Registro con errores");
                        return json_encode($data, true);
                    }               
                }
                else{
                    $data = array("Status"=>404, "Detalles"=>"El token es inválido");                               
                }
            }
            else
            {
                $data = array( "Status"=>404, "Detalles"=>"No está autorizado para guardar registros");     
            }
        }
        return json_encode($data, true);        
    }
    public function delete( $id ){
        $request = \Config\Services::request(); 
        $validation = \Config\Services::validation();
        $headers = $request->getHeaders();
        $model = new RegistrosModel();
        $registro=$model->where('estado', 1)
        ->findAll();
        foreach($registro as $key => $value){
            if(array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])){
                if($request->getHeader('Authorization')=='Authorization: Basic '.base64_encode($value["cliente_id"].":".$value["llave_secreta"])){
                            
                    $model = new ModulosModel();
                    $modulos = $model->where('estado', 1)
                    ->find( $id );
                    if(empty($modulos)){
                        $data = array(
                            "Status"=>404,
                            "Detalles"=>"No hay ningún modulos con este id"   
                        );
                    }
                    else{
                        $datos = array('estado' => 0 );
                        $modulos = $model->update($id, $datos);
                        $data = array(
                            "Status" => 200,
                            "Detalles" => "Se ha borrado con éxito"
                        );
                        return json_encode($data, true);
                    }   
                                    
                }
                else{
                    $data = array(
                        "Status"=>404,
                        "Detalles"=>"El token es inválido"
                    );                              
                }
            }
            else
            {
                $data = array( "Status"=>404, "Detalles"=>"No está autorizado para guardar registros");     
            }
        }
        return json_encode($data, true);
    }


}
