<?php

namespace App\Models;

use App\Traits\FormatsCurrency;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Variation extends Model
{
    use FormatsCurrency;

    /**
     * Os atributos que podem ser atribuídos em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'price',
    ];

    /**
     * Relacionamento: esta variação possui um controle de estoque.
     */
    public function stock(): HasOne
    {
        return $this->hasOne(Stock::class);
    }

    /**
     * Relacionamento: esta variação pertence a um produto.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Acessor: retorna o preço da variação formatado em reais (R$).
     */
    public function getPriceInReaisAttribute(): string
    {
        return $this->formatCurrency($this->price);
    }
}
