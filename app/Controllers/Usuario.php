<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\UsuarioModel; // Kuchiyose no jutsu del modelo

class Usuario extends Controller
{
	public function index()
	{
		$model = new UsuarioModel();          // Creando el objeto
		$usuario = $model->where('estado',1)
		->findAll();                      // usuario contiene toda la tabla
		if(count($usuario)==0){
			$respuesta = array(
				'error' => true,
				'mensaje' => "No hay elementos"
			);
			$data = json_encode($respuesta);
		}
		else{
			$data = json_encode($usuario);    // imprimir con json usuario	
		}
		return $data;   
	}
	public function show( $id ) //pueden colocar un nombre diferente a show
	{
		$model = new UsuarioModel();
		$usuario = $model->where('estado', 1)
		->find( $id );
		if(is_null($usuario)){
			$data = json_encode("El Id no existe");
		}
		else{
			$data = json_encode($usuario);
		}
		echo $data;
	}
	public function create() //puede ser nombre diferente a create
	{
	    $data=array(
        	'nombre'=>'jose',
        	'apellido'=>'hilario',
        	'nick'=>'josemax',
        	'clave'=>'12345678987654321',
        	'direccion'=>'jr. nicolas de pierola 351',
        	'dni'=>'65432126',
        	'imagen'=>'qwert',

    );
        $model=new UsuarioModel();
        $usuario=$model-> insert($data);
        $data = json_encode($model->find($usuario));
        echo $data;
	}
	public function update( $id ) //puede ser nombre diferente a create
	{
		$data = array(
			'nombre'=>'jose max',
        	'apellido'=>'hilario arroyo',
        	'nick'=>'josemax351',
        	'clave'=>'1111111111111111111',
        	'direccion'=>'jr. nicolas de pierola 355',
        	'dni'=>'56782126',
        	'imagen'=>'awawaawaaa',
		);
		$model = new UsuarioModel();
		$usuario = $model->where('estado', 1)
		->find($id);
		if(is_null($usuario)){
			$data = json_encode("El Id no existe");
		}
		else{
			$usuario = $model->update( $id, $data );
			$data = json_encode($model->find($id));
		}
		echo $data;
		
	}
	public function delete( $id )
	{
		$data = array(
			'estado' => 0
		);
		$model = new UsuarioModel();
		$usuario = $model->where('estado', 1)
		->find($id);
		if(is_null($usuario)){
			$data = json_encode("El Id no existe");
		}
		else{
			$usuario = $model->update( $id, $data );
			$data = json_encode("Registro ".$id." eliminado");
		}
		echo $data;
	}

}