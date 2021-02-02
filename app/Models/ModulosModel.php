<?php 
namespace App\Models;
use CodeIgniter\Model;

class ModulosModel extends Model
{
	protected $table      = 'modulos';
	protected $primaryKey = 'modulo_id';
	protected $returnType     = 'array';
	protected $allowedFields = ['modulo_nombre', 'modulo_icono', 'modulo_url', 'modulo_padre', 'estado', 'modulo_orden'];
}