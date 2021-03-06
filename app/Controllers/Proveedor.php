<?php
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\ProveedorModel;
use App\Models\RegistrosModel;

class Proveedor extends Controller{
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
                            
                    $model = new ProveedorModel();
                    $proveedor = $model->where('estado', 1)
                    ->findAll();
                    if (empty($proveedor)) {
                        $data = array(
                            "Status"=>404,
                            "Total de resultados" => 0,
                            "Detalles"=>"No tenemos registros de productos en la base de datos"
                        );
                    }
                    
                    else{
                        $data = array(
                            "Status" => 200,
                            'Total de resultados' => count($proveedor),
                            "Detalles" => $proveedor
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
                            
                    $model = new ProveedorModel();
                    $proveedor = $model->where('estado', 1)
                    ->find( $id );
                    if (empty($proveedor)) {
                        $data = array(
                            "Status"=>404,
                            "Detalles"=>"No hay ningún proveedor con este id" 
                        );
                    }
                    else{
                        $data = array(
                            'Status' => 200,
                            "Detalles" => $proveedor
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
                        "nombres"=>$request->getVar("nombres"),
                        "apellido1"=>$request->getVar("apellido_paterno"),
                        "apellido2"=>$request->getVar("apellido_materno"),
                        "direccion"=>$request->getVar("direccion"),
                        "telefono"=>$request->getVar("telefono"),
                        "email"=>$request->getVar("email"),
                        "empresa"=>$request->getVar("empresa"),
                        "imagen"=>$request->getVar("imagen"),
                    );
                    if(!empty($datos)){
                        // Validar los datos
                        $validation->setRules([
                            'nombres' => 'required|string|max_length[255]',
                            'apellido_paterno' => 'required|string|max_length[255]',
                            'apellido_materno' => 'required|string|max_length[255]',
                            'direccion' => 'required|string|max_length[255]',
                            'telefono' => 'required|string|max_length[255]',
                            'email' => 'required|string|max_length[255]',
                            'empresa' => 'required|string|max_length[255]',
                            'imagen' => 'required|string|max_length[255]'   
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
                                "nombres"=>$datos["nombres"],
                                "apellido1"=>$datos["apellido1"],
                                "apellido2"=>$datos["apellido2"],
                                "direccion"=>$datos["direccion"],
                                "telefono"=>$datos["telefono"],
                                "email"=>$datos["email"],
                                "id_empresa"=>$datos["empresa"],
                                "imagen"=>$datos["imagen"]
                            );          
                            $model = new ProveedorModel();
                            $proveedor = $model->insert($datos);
                            $data = array(
                                "Status"=>200,
                                "Detalle"=>"Registro exitoso, datos de proveedor guardado"
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
                           'nombres' => 'required|string|max_length[255]',
                           'apellido_paterno' => 'required|string|max_length[255]',
                           'apellido_materno' => 'required|string|max_length[255]',
                           'direccion' => 'required|string|max_length[255]',
                           'telefono' => 'required|string|max_length[255]',
                           'email' => 'required|string|max_length[255]',
                           'empresa' => 'required|string|max_length[255]',
                           'imagen' => 'required|string|max_length[255]'
                        ]);
                        $validation->withRequest($this->request)
                        ->run();    
                        if($validation->getErrors()){
                            $errors = $validation->getErrors();
                            $data = array("Status"=>404, "Detalle"=>$errors);                           
                            return json_encode($data, true); 
                        }
                        else{
                            $model = new ProveedorModel();
                            $proveedor = $model->find($id);
                            $datos = array(
                                "nombres"=>$datos["nombres"],
                                "apellido1"=>$datos["apellido_paterno"],
                                "apellido2"=>$datos["apellido_materno"],
                                "direccion"=>$datos["direccion"],
                                "telefono"=>$datos["telefono"],
                                "email"=>$datos["email"],
                                "id_empresa"=>$datos["empresa"],
                                "imagen"=>$datos["imagen"]
                            );          
                            
                            $proveedor = $model->update($id, $datos);
                            $data = array(
                                "Status"=>200,
                                "Detalle"=>"Actualización exitosa, datos de proveedor modificado"
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
                            
                    $model = new ProveedorModel();
                    $proveedor = $model->where('estado', 1)
                    ->find( $id );
                    if(empty($proveedor)){
                        $data = array(
                            "Status"=>404,
                            "Detalles"=>"No hay ningún proveedor con este id"   
                        );
                    }
                    else{
                        $datos = array('estado' => 0 );
                        $proveedor = $model->update($id, $datos);
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






