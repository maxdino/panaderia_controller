<?php 
namespace App\Models;
use CodeIgniter\Model;

class Detalle_comprasModel extends Model
{
	protected $table      = 'detalle_compras';
	protected $primaryKey = ['id_compras','id_ingredientes'];
	protected $returnType     = 'array';
	protected $allowedFields = ['id_compras', 'id_ingredientes', 'cantidad', 'precio', 'id_unidad_medida'];
}