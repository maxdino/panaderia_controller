<?php
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Unidad_medidaModel;
use App\Models\RegistrosModel;

class unidad_medida extends Controller{
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
                            
                    $model = new Unidad_medidaModel();
                    $unidad_medida = $model->where('estado', 1)
                    ->findAll();
                    if (empty($unidad_medida)) {
                        $data = array(
                            "Status"=>404,
                            "Total de resultados" => 0,
                            "Detalles"=>"No tenemos registros de productos en la base de datos"
                        );
                    }
                    
                    else{
                        $data = array(
                            "Status" => 200,
                            'Total de resultados' => count($unidad_medida),
                            "Detalles" => $unidad_medida
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
                            
                    $model = new Unidad_medidaModel();
                    $unidad_medida = $model->where('estado', 1)
                    ->find( $id );
                    if (empty($unidad_medida)) {
                        $data = array(
                            "Status"=>404,
                            "Detalles"=>"No hay ningún unidad_medida con este id" 
                        );
                    }
                    else{
                        $data = array(
                            'Status' => 200,
                            "Detalles" => $unidad_medida
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
                        "unidad_medida"=>$request->getVar("unidad_medida"),
                        "cantidad"=>$request->getVar("cantidad"),
                        "empresa"=>$request->getVar("empresa")
                    );
                    if(!empty($datos)){
                        // Validar los datos
                        $validation->setRules([
                            'unidad_medida' => 'required|string|max_length[255]',
                            'cantidad' => 'required|string|max_length[255]',
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
                                "unidad_medida"=>$datos["unidad_medida"],
                                "cantidad"=>$datos["cantidad"],
                                "id_empresa"=>$datos["empresa"]
                            );          
                            $model = new Unidad_medidaModel();
                            $unidad_medida = $model->insert($datos);
                            $data = array(
                                "Status"=>200,
                                "Detalle"=>"Registro exitoso, datos de unidad_medida guardado"
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
                          'unidad_medida' => 'required|string|max_length[255]',
                            'cantidad' => 'required|string|max_length[255]',
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
                            $model = new Unidad_medidaModel();
                            $unidad_medida = $model->find($id);
                            $datos = array(
                                "unidad_medida"=>$datos["unidad_medida"],
                                "cantidad"=>$datos["cantidad"],
                                "id_empresa"=>$datos["empresa"]
                            );          
                            
                            $unidad_medida = $model->update($id, $datos);
                            $data = array(
                                "Status"=>200,
                                "Detalle"=>"Actualización exitosa, datos de unidad_medida modificado"
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
                            
                    $model = new Unidad_medidaModel();
                    $unidad_medida = $model->where('estado', 1)
                    ->find( $id );
                    if(empty($unidad_medida)){
                        $data = array(
                            "Status"=>404,
                            "Detalles"=>"No hay ningún unidad_medida con este id"   
                        );
                    }
                    else{
                        $datos = array('estado' => 0 );
                        $unidad_medida = $model->update($id, $datos);
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






