<?php 
namespace App\Models;
use CodeIgniter\Model;

class Unidad_medidaModel extends Model
{
	protected $table      = 'unidad_medida';
	protected $primaryKey = 'id_unidad_medida';
	protected $returnType     = 'array';
	protected $allowedFields = ['unidad_medida', 'cantidad','id_empresa','estado'];
}