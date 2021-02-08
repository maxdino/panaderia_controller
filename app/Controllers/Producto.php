<?php
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\ProductoModel;
use App\Models\Unidad_medidaModel;
use App\Models\RegistrosModel;

class producto extends Controller{
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
                            
                    $model = new ProductoModel();
                    $producto = $model->listar_producto();

                    if (empty($producto)) {
                        $data = array(
                            "Status"=>404,
                            "Total de resultados" => 0,
                            "Detalles"=>"No tenemos registros de productos en la base de datos"
                        );
                    }
                    
                    else{
                        $data = array(
                            "Status" => 200,
                            'Total de resultados' => count($producto),
                            "Detalles" => $producto
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
                            
                    $model = new ProductoModel();
                    $producto = $model->where('estado', 1)
                    ->find( $id );
                    if (empty($producto)) {
                        $data = array(
                            "Status"=>404,
                            "Detalles"=>"No hay ningún producto con este id" 
                        );
                    }
                    else{
                        $data = array(
                            'Status' => 200,
                            "Detalles" => $producto
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
                        "producto"=>$request->getVar("producto"),
                        "precio"=>$request->getVar("precio"),
                        "cantidad"=>$request->getVar("cantidad"),
                        "imagen"=>$request->getVar("imagen"),
                        "categoria"=>$request->getVar("categoria"),
                        "unidad_medida"=>$request->getVar("unidad_medida"),
                        "empresa"=>$request->getVar("empresa")
                    );
                    if(!empty($datos)){
                        // Validar los datos
                        $validation->setRules([
                            'producto' => 'required|string|max_length[255]',
                            'precio' => 'required|string|max_length[255]',
                            'cantidad' => 'required|string|max_length[255]',
                            'imagen' => 'required|string|max_length[255]',
                            'empresa' => 'required|string|max_length[255]', 
                            'unidad_medida' => 'required|string|max_length[255]', 
                            'categoria' => 'required|string|max_length[255]' 
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
                                "descripcion"=>$datos["producto"],
                                "precio"=>number_format($datos["precio"], 2, '.', ''),
                                "cantidad"=>$datos["cantidad"],
                                "imagen"=>$datos["imagen"],
                                "id_empresa"=>$datos["empresa"],
                                "id_unidad_medida"=>$datos["unidad_medida"],
                                "id_categoria"=>$datos["categoria"],
                            );          
                            $model = new ProductoModel();
                            $producto = $model->insert($datos);
                            $data = array(
                                "Status"=>200,
                                "Detalle"=>"Registro exitoso, datos de producto guardado"
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
                           'producto' => 'required|string|max_length[255]',
                            'precio' => 'required|string|max_length[255]',
                            'cantidad' => 'required|string|max_length[255]',
                            'imagen' => 'required|string|max_length[255]',
                            'empresa' => 'required|string|max_length[255]', 
                            'unidad_medida' => 'required|string|max_length[255]', 
                            'categoria' => 'required|string|max_length[255]' 
                        ]);
                        $validation->withRequest($this->request)
                        ->run();    
                        if($validation->getErrors()){
                            $errors = $validation->getErrors();
                            $data = array("Status"=>404, "Detalle"=>$errors);                           
                            return json_encode($data, true); 
                        }
                        else{
                            $model = new ProductoModel();
                            $producto = $model->find($id);
                            $datos = array(
                                "descripcion"=>$datos["producto"],
                                "precio"=>number_format($datos["precio"], 2, '.', ''),
                                "cantidad"=>$datos["cantidad"],
                                "imagen"=>$datos["imagen"],
                                "id_empresa"=>$datos["empresa"],
                                "id_unidad_medida"=>$datos["unidad_medida"],
                                "id_categoria"=>$datos["categoria"],
                            );          
                            
                            $producto = $model->update($id, $datos);
                            $data = array(
                                "Status"=>200,
                                "Detalle"=>"Actualización exitosa, datos de producto modificado"
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
                            
                    $model = new ProductoModel();
                    $producto = $model->where('estado', 1)
                    ->find( $id );
                    if(empty($producto)){
                        $data = array(
                            "Status"=>404,
                            "Detalles"=>"No hay ningún producto con este id"   
                        );
                    }
                    else{
                        $datos = array('estado' => 0 );
                        $producto = $model->update($id, $datos);
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






