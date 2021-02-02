<?php 
namespace App\Models;
use CodeIgniter\Model;

class RegistrosModel extends Model
{
	protected $table = "empleados";
	protected $primaryKey = 'idEmpleado';
  	protected $returnType = 'array';
   	protected $allowedFields = ['nombres', 'apellido_paterno','apellido_materno','id_empresa','perfil_id', "email", "cliente_id", 'llave_secreta', 'estado'];
}   		