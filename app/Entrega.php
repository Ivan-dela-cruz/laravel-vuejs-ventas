<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Entrega extends Model
{
    protected $fillable = [
        'id_provedor',
        'cantidadM',
        'cantidadT',
        'total',
        'precio',
        'valor'
    ];

    public function proveedor()
    {
        return $this->belongsTo('App\Proveedor','id_provedor');
    }
}
