<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MaterialDirecto extends Model
{
    protected $fillable = [
        'nombre',
        'cantidad',
        'detalle',
        'condicion'
    ];
}
