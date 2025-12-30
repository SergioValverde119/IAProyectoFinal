<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Alimento extends Model
{
    use HasFactory;

    protected $table = 'alimentos';

    protected $fillable = [
        'nombre',
        'tipo',
        'calorias',
        'proteinas',
        'carbohidratos',
        'grasas',
        'porcion'
    ];
}