<?php
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\RegistrosModel;
use App\Models\PermisosModel;

class Permisos extends Controller{

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
		 			$permisos = $model->getTraerpermisos($id);
		 			$modulo = $model->getTraermodulo();
		 			 
					if (empty($modulo)) {
						$data = array(
							"Status"=>404,
							"Total de resultados" => 0,
							"Detalles"=>"No tenemos registros de modulo en la base de datos"
						);
					}
					else{
						$data = array(
							"Status" => 200,
							'Total de resultados' => count($modulo),
							"permisos" => $permisos,
							"modulo" => $modulo,
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