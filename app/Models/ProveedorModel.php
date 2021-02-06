<?php 
namespace App\Models;
use CodeIgniter\Model;

class ProveedorModel extends Model
{
	protected $table      = 'proveedor';
	protected $primaryKey = 'id_proveedor';
	protected $returnType     = 'array';
	protected $allowedFields = ['nombres', 'apellido1', 'apellido2', 'direccion', 'telefono','email','id_empresa','imagen' ,'estado'];
}