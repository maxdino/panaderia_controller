<?php 
namespace App\Models;
use CodeIgniter\Model;

class ProductoModel extends Model
{
	protected $table      = 'productos';
	protected $primaryKey = 'idProducto';
	protected $returnType     = 'array';
	protected $allowedFields = ['descripcion', 'cantidad', 'precio', 'imagen', 'id_empresa','id_categoria','id_unidad_medida','estado'];

	function listar_producto(){
		return $this->db->table('productos p')
		->select('p.idProducto,p.descripcion,p.cantidad,p.precio,c.categoria,p.id_empresa')
		->where("p.estado",'1')
		->join("categoria as c","c.id_categoria=p.id_categoria")
		->get()->getResultArray();
	}

	function nuevo_producto(){
		return $this->db->table('productos p')
		->select('p.idProducto,p.descripcion,p.precio,p.cantidad,p.id_empresa')
		->where("p.estado",'1')
		->join("categoria as c","c.id_categoria=p.id_categoria")
		->orderBy("p.idProducto desc")
		->get()->getResultArray();
	}

	function producto_mas_vendido(){
		return $this->db->table('productos p')
		->select('p.idProducto,p.descripcion,p.precio,p.id_empresa,p.cantidad,(SELECT count(dv.idProducto) from detalleventas as dv where dv.idProducto=p.idProducto) as vendidos')
		->where("p.estado",'1')
		->orderBy("p.idProducto asc")
		->get()->getResultArray();
	}
}