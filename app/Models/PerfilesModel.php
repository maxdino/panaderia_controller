<?php 
namespace App\Models;
use CodeIgniter\Model;

class PerfilesModel extends Model
{
	protected $table      = 'perfiles';
	protected $primaryKey = 'perfil_id';
	protected $returnType     = 'array';
	protected $allowedFields = ['perfil_descripcion', 'perfil_url','estado', 'id_empresa'];
}