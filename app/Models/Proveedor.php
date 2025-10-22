<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;

    protected $table = 'proveedor'; 
    protected $primaryKey = 'idProveedor';
    public $timestamps = true; 

    protected $fillable = [
        'nombre',
        'apellidoPaterno',
        'apellidoMaterno',
        'telefono',
        'direccion',
        'email',
        'rucProveedor',
        'estado',
        'calificacion',
        'puntualidad',
        'calidad',
        'precio',
        'incumplimientos',
    ];

    public function getNombreCompletoAttribute()
    {
        return "{$this->nombre} {$this->apellidoPaterno} {$this->apellidoMaterno}";
    }

    // Relación con documentos adjuntos
    public function documentos()
    {
        return $this->hasMany(DocumentoProveedor::class, 'idProveedor', 'idProveedor');
    }

    // Relación con compras
    public function compras()
    {
        return $this->hasMany(Compra::class, 'idProveedor', 'idProveedor');
    }
}