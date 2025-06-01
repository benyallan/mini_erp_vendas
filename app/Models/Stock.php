<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stock extends Model
{
    /** @use HasFactory<\Database\Factories\StockFactory> */
    use HasFactory;

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

    public function decrementQuantity(int $amount): void
    {
        $this->decrement('quantity', $amount);
    }
}
