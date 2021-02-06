<?php 
namespace App\Models;
use CodeIgniter\Model;

class IngredientesModel extends Model
{
	protected $table      = 'ingredientes';
	protected $primaryKey = 'id_ingredientes';
	protected $returnType     = 'array';
	protected $allowedFields = ['ingredientes', 'cantidad','id_empresa','id_unidad_medida','estado'];

	function listar_ingredientes(){
		return $this->db->table('ingredientes t')
		->select('t.id_ingredientes,t.ingredientes,t.cantidad,t.id_empresa,u.unidad_medida')
		->where('t.estado',1)
		->join("unidad_medida as u","u.id_unidad_medida=t.id_unidad_medida")
		->get()->getResultArray();
	}
}