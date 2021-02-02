<?php
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\EmpresaModel;
use App\Models\RegistrosModel;

class Empresa extends Controller{
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
					    	
					$model = new EmpresaModel();
					$empresa = $model->where('estado', 1)
					->findAll();
					if (empty($empresa)) {
						$data = array(
							"Status"=>404,
							"Total de resultados" => 0,
							"Detalles"=>"No tenemos registros de productos en la base de datos"
						);
					}
					
					else{
						$data = array(
							"Status" => 200,
							'Total de resultados' => count($empresa),
							"Detalles" => $empresa
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
					    	
					$model = new EmpresaModel();
					$empresa = $model->where('estado', 1)
					->find( $id );
					if (empty($empresa)) {
						$data = array(
							"Status"=>404,
							"Detalles"=>"No hay ningún empresa con este id"	
						);
					}
					else{
						$data = array(
							'Status' => 200,
							"Detalles" => $empresa
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
						"descripcion"=>$request->getVar("descripcion"),
						"ruc"=>$request->getVar("ruc"),
						"imagen"=>$request->getVar("imagen")
					);
					if(!empty($datos)){
						// Validar los datos
						$validation->setRules([
							'descripcion' => 'required|string|max_length[255]',
							'ruc' => 'required|string|max_length[255]',
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
								"descripcion"=>$datos["descripcion"],
								"ruc"=>$datos["ruc"],
								"imagen"=>$datos["imagen"]
							);        	
		        			$model = new EmpresaModel();
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
							'descripcion' => 'required|string|max_length[255]',
							'ruc' => 'required|string|max_length[255]',
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
							$model = new EmpresaModel();
							$proveedor = $model->find($id);
							$datos = array(
								"descripcion"=>$datos["descripcion"],
								"ruc"=>$datos["ruc"],
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
					    	
					$model = new EmpresaModel();
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






