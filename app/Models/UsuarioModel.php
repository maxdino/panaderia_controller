<?php 
namespace App\Models;
use CodeIgniter\Model;

class UsuarioModel extends Model
{
	protected $table      = 'usuario';
	protected $primaryKey = 'id_usuario';
	protected $returnType     = 'array';
	protected $allowedFields = ['nombre','apellido','nick','clave','direccion','dni','imagen','estado'];
}