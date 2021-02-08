<?php 
namespace App\Models;
use CodeIgniter\Model;

class ComprasModel extends Model
{
	protected $table      = 'compras';
	protected $primaryKey = 'id_compras';
	protected $returnType     = 'array';
	protected $allowedFields = ['id_proveedor', 'fecha', 'id_empresa', 'numero_correlativo','monto'];

	function listar_compras(){
		return $this->db->table('compras c')
		->select('p.nombres,p.apellido1,p.apellido2,c.fecha,c.id_empresa,c.numero_correlativo,c.monto')
		->where("c.estado",1)
		->join("proveedor as p","p.id_proveedor=c.id_proveedor")
		->orderBy("c.id_compras desc")
		->get()->getResultArray();
	}
}