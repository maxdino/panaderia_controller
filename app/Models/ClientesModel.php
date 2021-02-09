<?php 
namespace App\Models;
use CodeIgniter\Model;

class ClientesModel extends Model
{
	protected $table      = 'clientes';
	protected $primaryKey = 'idCliente';
	protected $returnType     = 'array';
	protected $allowedFields = ['nombres', 'apellido1', 'apellido2', 'direccion', 'telefono','email','clave','id_empresa', 'estado'];
}