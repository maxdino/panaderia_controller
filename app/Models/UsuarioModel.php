<?php 
namespace App\Models;
use CodeIgniter\Model;

class UsuarioModel extends Model
{
	protected $table      = 'empleados';
	protected $primaryKey = 'idEmpleado';
	protected $returnType     = 'array';
	protected $allowedFields = ['nombres','apellido_paterno','apellido_materno','id_empresa','perfil_id','llave_secreta','cliente_id','email','imagen','telefono','direccion','estado'];
}