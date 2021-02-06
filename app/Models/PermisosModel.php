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

	public function gettraerpadrebarra($idperfil){
		return $this->db->table('permisos_sede')
		->select('p.modulo_id,p.modulo_nombre,p.modulo_icono as icono')
		->where("persed_id_perfil",$idperfil)
		->join("modulos as h","h.modulo_id=permisos_sede.persed_id_modulo")
		->join("modulos as p","h.modulo_padre=p.modulo_id")
		->groupBy('p.modulo_id')
		->orderBy("p.modulo_orden","asc")
		->get()->getResultArray();
	}

	public function gettraerhijobarra($idperfil){
		return $this->db->table('permisos_sede')
		->select('h.modulo_id,h.modulo_nombre,h.modulo_padre,h.modulo_url')
		->where("persed_id_perfil",$idperfil)
		->join("modulos as h","h.modulo_id=permisos_sede.persed_id_modulo")
		->join("modulos as p","h.modulo_padre=p.modulo_id")
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

/*	public function getTraerpermisosmodulo($idperfil){
		return $this->db->table('modulo h')
		->select('SELECT modulohijo.modulo_id,modulohijo.modulo_padre,modulohijo.modulo_nombre as nombre_padre')
		->where("pu.id_perfil_usuario",$idperfil)
		->where('p.estado', 1)
		->join("modulo as pa","h.id_modulo=pa.id_padre")
		->join("permisos as p","p.id_modulo=pa.id_modulo")
		->join("perfil_usuario as pu","pu.id_perfil_usuario=p.id_perfil_usuario")
		->orderBy("h.id_modulo","asc")
		->get()->getResultArray();
	}*/
	public function getmodulopadre(){
		return $this->db->table('modulos')
		->select('modulohijo.modulo_id,modulohijo.modulo_padre,modulohijo.modulo_nombre as nombre_padre')
		->where("modulohijo.modulo_id != 1 ")
		->join("modulos AS modulohijo","modulos.modulo_padre = modulohijo.modulo_id")
		->groupBy('modulohijo.modulo_padre,modulohijo.modulo_nombre')
		->get()->getResultArray();
	}

	public function getmoduloshijo($id){
		return $this->db->table('modulos')
		->select('modulos.modulo_id,modulos.modulo_nombre')
		->where("modulohijo.modulo_id",$id)
		->join("modulos AS modulohijo","modulos.modulo_padre = modulohijo.modulo_id")
		->get()->getResultArray();
	}

}
