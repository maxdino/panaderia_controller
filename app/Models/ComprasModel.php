<?php 
namespace App\Models;
use CodeIgniter\Model;

class ComprasModel extends Model
{
	protected $table      = 'compras';
	protected $primaryKey = 'id_compras';
	protected $returnType     = 'array';
	protected $allowedFields = ['id_proveedor', 'fecha', 'id_empresa', 'numero_correlativo','monto'];
}