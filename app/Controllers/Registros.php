<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\RegistrosModel; // Kuchiyose no jutsu del modelo

class Registros extends Controller
{
	public function index()
	{
		$model = new RegistrosModel();          
		$registro = $model->where('estado',1)
		->findAll();
		if(count($registro)==0){
			$respuesta = array('error'=>true, "mensaje"=>"No hay elementos");
			$data = json_encode($respuesta);
		}
		else{
			$data = json_encode($registro);
		}                      
		return $data;     
	}
	
	public function create(){
		$request = \Config\Services::request(); // Trae datos del POSTMAN
		$validation = \Config\Services::validation(); // Valida datos
		// Solicitando los datos del POSTMAN
		$datos = array(
			'nombres' =>$request->getVar("nombres"),
			'apellido_paterno' => $request->getVar("apellido_paterno"),
			'apellido_materno' =>$request->getVar("apellido_materno"),
			'email' => $request->getVar("email"),
			'id_empresa' => $request->getVar("empresa"),
			"perfil_id" => $request->getVar("perfil"),
		);
		// Validando los datos que llegan a través del POSTMAN
		if(!empty($datos)) {
			$validation->setRules([
				'nombres' => 'required|string|max_length[255]',
				'apellido_paterno' => 'required|string|max_length[255]',
				'apellido_materno' => 'required|string|max_length[255]',
				'email' => 'required|valid_email',
				'empresa' => 'required|integer|max_length[11]',
				'perfil' => 'required|integer|max_length[11]'
			]);

			$validation->withRequest($this->request)
			->run(); // Corriendo las validaciones
			if($validation->getErrors()){
				$errors = $validation->getErrors();
				$data = array(
					"Status"=>404,
					"Detalle" => $errors
				);
				return json_encode($data, true);
			}
			else{
				$cliente_id = crypt($datos['nombres'].$datos["apellido_paterno"].$datos["apellido_materno"].$datos["email"], '$2a$07$dfhdfrexfhgdfhdferttgsad$');
	     		$llave_secreta = crypt($datos["email"].$datos["apellido_paterno"].$datos["apellido_materno"].$datos["nombres"], '$2a$07$dfhdfrexfhgdfhdferttgsad$');
				$datos = array(
					"nombres" => $datos["nombres"],
					"apellido_paterno" => $datos["apellido_paterno"],
					"apellido_materno" => $datos["apellido_materno"],
					"email" => $datos["email"],
					"id_empresa" => $datos["id_empresa"],
					"perfil_id" => $datos["perfil_id"],
					"cliente_id" => str_replace('$','a', $cliente_id),
					"llave_secreta" => str_replace('$','o', $llave_secreta)
				);
				$model = new RegistrosModel();
				$registro = $model->insert( $datos ); 
				// var_dump($registro); die;
				$data = array(
					"Status" => 200,
					"Detalle" => "Registro exitoso, guarde sus credenciales",
					"credenciales" => array(
						"cliente_id" => str_replace('$','a', $cliente_id),
						"llave_secreta" => str_replace('$','o', $llave_secreta)
					)
				);
				return json_encode($data, true);
			}
		}
		else{
			$data = array(
				"Status" => 404,
				"Detalle" => "Registro se realizó con errores"
			);
			return json_encode($data, true);
		}
	}
}