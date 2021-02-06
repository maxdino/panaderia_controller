<?php
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\RegistrosModel;
use App\Models\PermisosModel;

class Permisos_modulo extends Controller{

	public function index(){
	}

	public function show($id){
		$request = \Config\Services::request(); 
		$validation = \Config\Services::validation();
		$headers = $request->getHeaders();
		$model = new RegistrosModel();
		$registro=$model->where('estado', 1)
		->findAll();
		foreach($registro as $key => $value){
		  	if(array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])){
				if($request->getHeader('Authorization')=='Authorization: Basic '.base64_encode($value["cliente_id"].":".$value["llave_secreta"])){
				 
					$model = new PermisosModel();
		 			$datos = $model->getmodulopadre();
		 			 foreach ($datos as $key => $value) {
		 			 	$modulo = $model->getmoduloshijo( $value['modulo_id']);
		 			 	$datos[$key]['lista'] = $modulo;
		 			 }

		 			$datos[$key+1]["permisos"] = $model->getTraerpermisos($id);
					if (empty($datos)) {
						$data = array(
							"Status"=>404,
							"Total de resultados" => 0,
							"Detalles"=>"No tenemos registros de modulo en la base de datos"
						);
					}
					else{
						$data = array(
							"Status" => 200,
							'Total de resultados' => count($datos),
							"Detalles" => $datos
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
                    );
                    if(!empty($datos)){
                        // Validar los datos
                        $validation->setRules([
                            'perfil' => 'required|string|max_length[255]',
                        ]);
                        $validation->withRequest($this->request)
                        ->run();    
                        if($validation->getErrors()){
                            $errors = $validation->getErrors();
                            $data = array("Status"=>404, "Detalle"=>$errors);                           
                            return json_encode($data, true); 
                        }
                        else{
                            $model = new PermisosModel(); 
                            $model->where('persed_id_perfil',$datos['perfil'])->delete();
                            
                            for ($i=0; $i < count($_POST['permisos']); $i++) { 
                              var_dump($_POST['permisos'][$i]);
                              $datos1 = array(
                                "persed_id_perfil" => $datos["perfil"],
                                "persed_id_modulo" => $_POST['permisos'][$i]
                            );
                            $model = new PermisosModel();
                            $model->insert($datos1);
                            }
 
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

}