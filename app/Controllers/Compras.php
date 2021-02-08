<?php
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\ComprasModel;
use App\Models\Detalle_comprasModel;
use App\Models\Unidad_medidaModel;
use App\Models\IngredientesModel;
use App\Models\RegistrosModel;

class Compras extends Controller{
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
                            
                    $model = new ComprasModel();
                    $compras = $model->listar_compras();

                    if (empty($compras)) {
                        $data = array(
                            "Status"=>404,
                            "Total de resultados" => 0,
                            "Detalles"=>"No tenemos registros de productos en la base de datos"
                        );
                    }
                    
                    else{
                        $data = array(
                            "Status" => 200,
                            'Total de resultados' => count($compras),
                            "Detalles" => $compras
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
                            
                    $model = new ComprasModel();
                    $compras = $model->where('estado', 1)
                    ->find( $id );
                    if (empty($compras)) {
                        $data = array(
                            "Status"=>404,
                            "Detalles"=>"No hay ningún compras con este id" 
                        );
                    }
                    else{
                        $data = array(
                            'Status' => 200,
                            "Detalles" => $compras
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
                        "proveedor"=>$request->getVar("proveedor"),
                        "fecha"=>$request->getVar("fecha"),
                        "empresa"=>$request->getVar("empresa"),
                        "numero_correlativo"=>$request->getVar("numero_correlativo"),
                        "monto"=>$request->getVar("monto")
                    );
                    if(!empty($datos)){
                        // Validar los datos
                        $validation->setRules([
                            'proveedor' => 'required|string|max_length[255]',
                            'fecha' => 'required|string|max_length[255]',
                            'empresa' => 'required|string|max_length[255]',
                            'numero_correlativo' => 'required|string|max_length[255]',
                            'monto' => 'required|string|max_length[255]' 
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
                                "id_proveedor"=>$datos["proveedor"],
                                "fecha"=>$datos["fecha"],
                                "id_empresa"=>$datos["empresa"],
                                "numero_correlativo"=>$datos["numero_correlativo"],
                                "monto"=>number_format($datos["monto"], 2, '.', '')
                            );          
                            $model = new ComprasModel();
                            $compras = $model->insert($datos);
                           for ($i=0; $i < count($_POST['producto']) ; $i++) {
                               $datos1 = array(
                                "id_compras"=>$compras,
                                "id_ingredientes"=>$_POST['producto'][$i],
                                "cantidad"=>$_POST['cantidad'][$i],
                                "precio"=>$_POST['precio'][$i],
                                "id_unidad_medida"=>$_POST['unidad_medida'][$i]
                            );          
                            $model = new Detalle_comprasModel();
                            $detallecompras = $model->insert($datos1);
                            $model = new IngredientesModel();
                            $cantidad_ingrediente = $model->find($_POST['producto'][$i]);
                            $cantidad_actual=$cantidad_ingrediente['cantidad'];
                            $model = new Unidad_medidaModel();
                            $unidad = $model->find($_POST['unidad_medida'][$i]);
                              $cantidad=$unidad['cantidad']*$_POST['cantidad'][$i];
                               $datos2 = array(
                                "cantidad"=>$cantidad+$cantidad_actual
                            ); 
                               $model = new IngredientesModel();
                               $ingredientes = $model->update($_POST['producto'][$i], $datos2);
                            
                            }
                             
                            $data = array(
                                "Status"=>200,
                                "Detalle"=>"Registro exitoso, datos de compras guardado"
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
    
}






