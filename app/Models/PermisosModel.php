<?php
namespace App\Models;
use CodeIgniter\Model;

class PermisosModel extends Model{
	
	protected $table = 'permisos_sede';
    protected $primaryKey = ['persed_id_perfil','persed_id_modulo'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
    protected $allowedFields = ['persed_id_perfil','persed_id_modulo'];

	public function getTraerpermisos($idperfil){
		return $this->db->table('modulos h')
		->select('h.modulo_orden,h.modulo_id as idpadre, h.modulo_nombre as padre, pa.modulo_nombre as hijo, pa.modulo_id as idhijo, h.modulo_icono as icono, pa.modulo_url')
		->where("pu.perfil_id",$idperfil)
		->join("modulos as pa","h.modulo_id=pa.modulo_padre")
		->join("permisos_sede as p","p.persed_id_modulo=pa.modulo_id")
		->join("perfiles as pu","pu.perfil_id=p.persed_id_perfil")
		->orderBy("h.modulo_orden","asc")
		->get()->getResultArray();
	}

	 public function getTraermodulo(){
		return $this->db->table('modulos h')
		->select('h.modulo_id as idpadre, h.modulo_nombre as padre, pa.modulo_nombre as hijo, pa.modulo_id as idhijo, h.modulo_icono as icono, pa.modulo_url,pa.estado')
		->join("modulos as pa","h.modulo_id=pa.modulo_padre")
		->where('pa.estado', 1)
		->orderBy("h.modulo_id","asc")
		->get()->getResultArray();
	}

	public function getTraermodulomenu($idperfil){
		return $this->db->table('modulo h')
		->select('h.id_modulo as idpadre, h.nombre as padre, pa.nombre as hijo, pa.id_modulo as idhijo, h.icono as icono, pa.url, p.estado')
		->where("pu.id_perfil_usuario",$idperfil)
		->where('p.estado', 1)
		->join("modulo as pa","h.id_modulo=pa.id_padre")
		->join("permisos as p","p.id_modulo=pa.id_modulo")
		->join("perfil_usuario as pu","pu.id_perfil_usuario=p.id_perfil_usuario")
		->orderBy("h.id_modulo","asc")
		->get()->getResultArray();
	}

}
