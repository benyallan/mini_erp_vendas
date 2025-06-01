<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stock extends Model
{
    /**
     * Os atributos que podem ser atribuídos em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'quantity',
    ];

    /**
     * Relacionamento: o estoque pertence a uma variação de produto.
     */
    public function variation(): BelongsTo
    {
        return $this->belongsTo(Variation::class);
    }
}
