<?php 
namespace App\Models;
use CodeIgniter\Model;

class EmpresaModel extends Model
{
	protected $table      = 'empresa';
	protected $primaryKey = 'id_empresa';
	protected $returnType     = 'array';
	protected $allowedFields = ['descripcion','ruc','imagen','estado'];
}