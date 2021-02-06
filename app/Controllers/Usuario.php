<?php
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\UsuarioModel;
use App\Models\RegistrosModel;

class Usuario extends Controller{
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

					$model = new UsuarioModel();
					$usuario = $model->where('estado', 1)
					->findAll();
					if (empty($usuario)) {
						$data = array(
							"Status"=>404,
							"Total de resultados" => 0,
							"Detalles"=>"No tenemos registros de usuario en la base de datos"
						);
					}

					else{
						$data = array(
							"Status" => 200,
							'Total de resultados' => count($usuario),
							"Detalles" => $usuario
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

					$model = new UsuarioModel();
					$usuario = $model->where('estado', 1)
					->find( $id );
					if (empty($usuario)) {
						$data = array(
							"Status"=>404,
							"Detalles"=>"No hay ningún usuario con este id" 
						);
					}
					else{
						$data = array(
							'Status' => 200,
							"Detalles" => $usuario
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
						"empresa"=>$request->getVar("empresa"),
						"perfil_id"=>$request->getVar("perfil"),
						"email"=>$request->getVar("email"),
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
							'empresa' => 'required|string|max_length[255]',
							'perfil' => 'required|string|max_length[255]',
							'email' => 'required|string|max_length[255]',
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
						/*	if($imagefile = $request->getFiles())
    						{
							foreach($imagefile['imagen'] as $img)
							{
								if ($img->isValid() && ! $img->hasMoved())
								{
									$cadena = str_replace(' ','', $img->getName());
									$imagen = "centro_turisticos/".$cadena;  
									move_uploaded_file($img->getTempName(),'librerias/imagen/'. $imagen);
									$datos = array(
										"imagen"=> $imagen,
										"id_centro_turistico"=> $ultimo,
									);
									$model = new Imgcentroturistico_m();
									$imgcentroturistico = $model->insert($datos); 
								}
							}
						}*/
							$cliente_id = crypt($datos['nombres'].$datos["apellido_paterno"].$datos["apellido_materno"].$datos["email"], '$2a$07$dfhdfrexfhgdfhdferttgsad$');
							$llave_secreta = crypt($datos["email"].$datos["apellido_paterno"].$datos["apellido_materno"].$datos["nombres"], '$2a$07$dfhdfrexfhgdfhdferttgsad$');
							$datos = array(
								"nombres"=>$datos["nombres"],
								"apellido_paterno"=>$datos["apellido1"],
								"apellido_materno"=>$datos["apellido2"],
								"direccion"=>$datos["direccion"],
								"telefono"=>$datos["telefono"],
								"id_empresa"=>$datos["empresa"],
								"perfil_id"=>$datos["perfil_id"],
								"email"=>$datos["email"],
								"imagen"=>$datos["imagen"],
								"cliente_id"=>str_replace('$','a', $cliente_id),
								"llave_secreta"=>str_replace('$','o', $llave_secreta)
							);          
							$model = new UsuarioModel();
							$usuario = $model->insert($datos);
							$data = array(
								"Status"=>200,
								"Detalle"=>"Registro exitoso, datos de usuario guardado"
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
							'empresa' => 'required|string|max_length[255]',
							'perfil' => 'required|string|max_length[255]',
							'email' => 'required|string|max_length[255]',
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
							$model = new UsuarioModel();
							$usuario = $model->find($id);
							$cliente_id = crypt($datos['nombres'].$datos["apellido_paterno"].$datos["apellido_materno"].$datos["email"], '$2a$07$dfhdfrexfhgdfhdferttgsad$');
							$llave_secreta = crypt($datos["email"].$datos["apellido_paterno"].$datos["apellido_materno"].$datos["nombres"], '$2a$07$dfhdfrexfhgdfhdferttgsad$');
							$datos = array(
								"nombres"=>$datos["nombres"],
								"apellido_paterno"=>$datos["apellido_paterno"],
								"apellido_materno"=>$datos["apellido_materno"],
								"direccion"=>$datos["direccion"],
								"telefono"=>$datos["telefono"],
								"id_empresa"=>$datos["empresa"],
								"perfil_id"=>$datos["perfil"],
								"email"=>$datos["email"],
								"imagen"=>$datos["imagen"],
								"cliente_id"=>str_replace('$','a', $cliente_id),
								"llave_secreta"=>str_replace('$','o', $llave_secreta)
							);          

							$usuario = $model->update($id, $datos);
							$data = array(
								"Status"=>200,
								"Detalle"=>"Actualización exitosa, datos de usuario modificado"
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

					$model = new UsuarioModel();
					$usuario = $model->where('estado', 1)
					->find( $id );
					if(empty($usuario)){
						$data = array(
							"Status"=>404,
							"Detalles"=>"No hay ningún usuario con este id"   
						);
					}
					else{
						$datos = array('estado' => 0 );
						$usuario = $model->update($id, $datos);
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






