<?php

namespace App\Models;

use App\Traits\FormatsCurrency;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    /** @use HasFactory<\Database\Factories\OrderItemFactory> */
    use FormatsCurrency, HasFactory;

    /**
     * Os atributos que podem ser atribuídos em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'variation_id',
        'quantity',
        'unit_price',
    ];

    /**
     * Relacionamento: este item pertence a um pedido.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relacionamento: este item pertence a uma variação de produto.
     */
    public function variation(): BelongsTo
    {
        return $this->belongsTo(Variation::class);
    }

    /**
     * Acessor: retorna o preço unitário formatado em reais (R$).
     *
     * @return string Preço formatado com separador decimal brasileiro.
     */
    public function getUnitPriceInReaisAttribute(): string
    {
        return $this->formatCurrency($this->unit_price);
    }

    /**
     * Acessor: retorna o valor total deste item (unit_price * quantity), formatado em reais (R$).
     *
     * @return string Total formatado com separador decimal brasileiro.
     */
    public function getTotalInReaisAttribute(): string
    {
        $total = $this->unit_price * $this->quantity;

        return $this->formatCurrency($total);
    }
}
