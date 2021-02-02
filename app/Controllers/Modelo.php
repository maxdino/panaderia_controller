<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\ModeloModel; // Kuchiyose no jutsu del modelo

class Modelo extends Controller
{
	public function index()
	{
		$model = new ModeloModel();          // Creando el objeto
		$modelo = $model->where('estado',1)
		->findAll();                      // modelo contiene toda la tabla
		if(count($modelo)==0){
			$respuesta = array(
				'error' => true,
				'mensaje' => "No hay elementos"
			);
			$data = json_encode($respuesta);
		}
		else{
			$data = json_encode($modelo);    // imprimir con json modelo	
		}
		return $data;   
	}
	public function show( $id ) //pueden colocar un nombre diferente a show
	{
		$model = new ModeloModel();
		$modelo = $model->where('estado', 1)
		->find( $id );
		if(is_null($modelo)){
			$data = json_encode("El Id no existe");
		}
		else{
			$data = json_encode($modelo);
		}
		echo $data;
	}
	public function create() //puede ser nombre diferente a create
	{
	    $data=array(
        	'modelo'=>'1234567',
        	'imagen'=>'qwert',
    );
        $model=new ModeloModel();
        $modelo=$model-> insert($data);
        $data = json_encode($model->find($modelo));
        echo $data;
	}
	public function update( $id ) //puede ser nombre diferente a create
	{
		$data = array(
			'modelo'=>'aaaaaaaaaa',
        	'imagen'=>'1111',
		);
		$model = new ModeloModel();
		$modelo = $model->where('estado', 1)
		->find($id);
		if(is_null($modelo)){
			$data = json_encode("El Id no existe");
		}
		else{
			$modelo = $model->update( $id, $data );
			$data = json_encode($model->find($id));
		}
		echo $data;
		
	}
	public function delete( $id )
	{
		$data = array(
			'estado' => 0
		);
		$model = new ModeloModel();
		$modelo = $model->where('estado', 1)
		->find($id);
		if(is_null($modelo)){
			$data = json_encode("El Id no existe");
		}
		else{
			$modelo = $model->update( $id, $data );
			$data = json_encode("Registro ".$id." eliminado");
		}
		echo $data;
	}

}