<?php namespace App\Models;

use CodeIgniter\Model;

class CantanteModel extends Model
{
    protected $table = 'cantantes';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nombre_natural','nombre_artistico','genero_musical','edad','pais_nacimiento'];

}
