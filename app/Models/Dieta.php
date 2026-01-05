<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dieta extends Model
{
    /**
     * Los atributos que se pueden asignar masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'nombre',
        'input_usuario',
        'resultado_ia',
        'analisis',
        'meta_calorica',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     * * Esto es CRÍTICO: permite que Laravel convierta el JSON de la base de datos
     * en un arreglo de PHP automáticamente al leerlo.
     */
    protected $casts = [
        'input_usuario' => 'array',
        'resultado_ia' => 'array',
        'analisis' => 'array',
    ];

    /**
     * Obtener el usuario dueño de esta dieta.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}