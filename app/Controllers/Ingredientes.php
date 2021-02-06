<?php
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\IngredientesModel;
use App\Models\RegistrosModel;

class Ingredientes extends Controller{
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
                            
                    $model = new IngredientesModel();
                    $ingredientes = $model->listar_ingredientes();
                    if (empty($ingredientes)) {
                        $data = array(
                            "Status"=>404,
                            "Total de resultados" => 0,
                            "Detalles"=>"No tenemos registros de productos en la base de datos"
                        );
                    }
                    
                    else{
                        $data = array(
                            "Status" => 200,
                            'Total de resultados' => count($ingredientes),
                            "Detalles" => $ingredientes
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
                            
                    $model = new IngredientesModel();
                    $ingredientes = $model->where('estado', 1)
                    ->find( $id );
                    if (empty($ingredientes)) {
                        $data = array(
                            "Status"=>404,
                            "Detalles"=>"No hay ningún ingredientes con este id" 
                        );
                    }
                    else{
                        $data = array(
                            'Status' => 200,
                            "Detalles" => $ingredientes
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
                        "ingredientes"=>$request->getVar("ingredientes"),
                        "cantidad"=>$request->getVar("cantidad"),
                        "empresa"=>$request->getVar("empresa"),
                         "unidad_medida"=>$request->getVar("unidad_medida"),
                    );
                    if(!empty($datos)){
                        // Validar los datos
                        $validation->setRules([
                            'ingredientes' => 'required|string|max_length[255]',
                            'cantidad' => 'required|string|max_length[255]',
                            'empresa' => 'required|string|max_length[255]',
                            'unidad_medida' => 'required|string|max_length[255]'
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
                                "ingredientes"=>$datos["ingredientes"],
                                "cantidad"=>$datos["cantidad"],
                                "id_empresa"=>$datos["empresa"],
                                "id_unidad_medida"=>$datos["unidad_medida"]
                            );          
                            $model = new IngredientesModel();
                            $ingredientes = $model->insert($datos);
                            $data = array(
                                "Status"=>200,
                                "Detalle"=>"Registro exitoso, datos de ingredientes guardado"
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
                           'ingredientes' => 'required|string|max_length[255]',
                           'cantidad' => 'required|string|max_length[255]',
                           'empresa' => 'required|string|max_length[255]',
                           'unidad_medida' => 'required|string|max_length[255]'
                        ]);
                        $validation->withRequest($this->request)
                        ->run();    
                        if($validation->getErrors()){
                            $errors = $validation->getErrors();
                            $data = array("Status"=>404, "Detalle"=>$errors);                           
                            return json_encode($data, true); 
                        }
                        else{
                            $model = new IngredientesModel();
                            $ingredientes = $model->find($id);
                            $datos = array(
                               "ingredientes"=>$datos["ingredientes"],
                                "cantidad"=>$datos["cantidad"],
                                "id_empresa"=>$datos["empresa"],
                                "id_unidad_medida"=>$datos["unidad_medida"]
                            );          
                            
                            $ingredientes = $model->update($id, $datos);
                            $data = array(
                                "Status"=>200,
                                "Detalle"=>"Actualización exitosa, datos de ingredientes modificado"
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
                            
                    $model = new IngredientesModel();
                    $ingredientes = $model->where('estado', 1)
                    ->find( $id );
                    if(empty($ingredientes)){
                        $data = array(
                            "Status"=>404,
                            "Detalles"=>"No hay ningún ingredientes con este id"   
                        );
                    }
                    else{
                        $datos = array('estado' => 0 );
                        $ingredientes = $model->update($id, $datos);
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






