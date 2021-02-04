<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\RegistrosModel; // Kuchiyose no jutsu del modelo

class Login extends Controller
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
			'usuario' =>$request->getVar("usuario"),
			'clave' => $request->getVar("clave"),
		);
		// Validando los datos que llegan a través del POSTMAN
		if(!empty($datos)) {
			$validation->setRules([
				'usuario' => 'required|string|max_length[255]',
				'clave' => 'required|string|max_length[255]'
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
				$model = new RegistrosModel();
				$registro = $model->verificar_usuario($datos["usuario"],$datos["clave"] );
				// var_dump($registro); die;
				if (isset($registro[0]['idEmpleado'])) {
					$data = array(
					"Status" => 200,
					"Detalle" => "Registro exitoso, guarde sus credenciales",
					"valida_usuario"  => 1,
					"usuario"  => $registro
				);
				}else{
					$data = array(
					"Status" => 200,
					"Detalle" => "Error, Usuario y Contraseña incorrecta",
					"valida_usuario"  => 0
				);
				}
				return json_encode($data, true);
			}
		}else{
			$data = array(
				"Status" => 404,
				"Detalle" => "Registro se realizó con errores"
			);
			return json_encode($data, true);
		}
	}
}