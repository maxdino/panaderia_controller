<?php 
namespace App\Models;
use CodeIgniter\Model;

class PerfilesModel extends Model
{
	protected $table      = 'perfiles';
	protected $primaryKey = 'perfil_id';
	protected $returnType     = 'array';
	protected $allowedFields = ['perfil_descripcion', 'perfil_url','estado', 'id_empresa'];

	function traerperfiles($id){
		return $this->db->table('perfiles')
		->select('*')
		->where("id_empresa",$id)
		->where("estado",'1')
		->get()->getResultArray();
	}
}