<?php 
namespace App\Models;
use CodeIgniter\Model;

class CategoriaModel extends Model
{
	protected $table      = 'categoria';
	protected $primaryKey = 'id_categoria';
	protected $returnType     = 'array';
	protected $allowedFields = ['categoria','imagen','estado', 'id_empresa'];
}