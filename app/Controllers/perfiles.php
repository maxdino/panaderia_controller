<?php
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\PerfilesModel;
use App\Models\RegistrosModel;

class Perfiles extends Controller{
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
                            
                    $model = new PerfilesModel();
                    $perfil = $model->where('estado', 1)
                    ->findAll();
                    if (empty($perfil)) {
                        $data = array(
                            "Status"=>404,
                            "Total de resultados" => 0,
                            "Detalles"=>"No tenemos registros de productos en la base de datos"
                        );
                    }
                    
                    else{
                        $data = array(
                            "Status" => 200,
                            'Total de resultados' => count($perfil),
                            "Detalles" => $perfil
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
                            
                    $model = new PerfilesModel();
                    $perfil = $model->where('estado', 1)
                    ->find( $id );
                    if (empty($perfil)) {
                        $data = array(
                            "Status"=>404,
                            "Detalles"=>"No hay ningún perfil con este id" 
                        );
                    }
                    else{
                        $data = array(
                            'Status' => 200,
                            "Detalles" => $perfil
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
                        "perfil"=>$request->getVar("perfil"),
                        "url"=>$request->getVar("url"),
                        "empresa"=>$request->getVar("empresa")
                    );
                    if(!empty($datos)){
                        // Validar los datos
                        $validation->setRules([
                            'perfil' => 'required|string|max_length[255]',
                            'url' => 'required|string|max_length[255]',
                            'empresa' => 'required|string|max_length[255]'
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
                                "perfil_descripcion"=>$datos["perfil"],
                                "perfil_url"=>$datos["url"],
                                "id_empresa"=>$datos["empresa"]
                            );          
                            $model = new PerfilesModel();
                            $perfil = $model->insert($datos);
                            $data = array(
                                "Status"=>200,
                                "Detalle"=>"Registro exitoso, datos de perfil guardado"
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
                          'perfil' => 'required|string|max_length[255]',
                          'url' => 'required|string|max_length[255]',
                          'empresa' => 'required|string|max_length[255]'
                        ]);
                        $validation->withRequest($this->request)
                        ->run();    
                        if($validation->getErrors()){
                            $errors = $validation->getErrors();
                            $data = array("Status"=>404, "Detalle"=>$errors);                           
                            return json_encode($data, true); 
                        }
                        else{
                            $model = new PerfilesModel();
                            $perfil = $model->find($id);
                            $datos = array(
                                "perfil_descripcion"=>$datos["perfil"],
                                "perfil_url"=>$datos["url"],
                                "id_empresa"=>$datos["empresa"]
                            );           
                            
                            $perfil = $model->update($id, $datos);
                            $data = array(
                                "Status"=>200,
                                "Detalle"=>"Actualización exitosa, datos de perfil modificado"
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
                            
                    $model = new PerfilesModel();
                    $perfil = $model->where('estado', 1)
                    ->find( $id );
                    if(empty($perfil)){
                        $data = array(
                            "Status"=>404,
                            "Detalles"=>"No hay ningún perfil con este id"   
                        );
                    }
                    else{
                        $datos = array('estado' => 0 );
                        $perfil = $model->update($id, $datos);
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
