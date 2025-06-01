<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    /**
     * Os atributos que podem ser atribuídos em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'status',
        'subtotal',
        'shipping_cost',
        'total',
        'postal_code',
        'address',
    ];

    protected $casts = [
        'status' => OrderStatus::class,
    ];

    protected $attributes = [
        'status' => OrderStatus::PENDING,
    ];

    /**
     * Relacionamento: retorna os itens associados a este pedido.
     *
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Relacionamento: retorna o cupom utilizado neste pedido (se houver).
     *
     * @return BelongsTo
     */
    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    /**
     * Acessor: retorna o total formatado em reais (R$).
     *
     * @return string Total do pedido com separador decimal brasileiro.
     */
    public function getTotalInReaisAttribute(): string
    {
        return $this->formatCurrency($this->total);
    }

    /**
     * Acessor: retorna o subtotal formatado em reais (R$).
     *
     * @return string Subtotal com separador decimal brasileiro.
     */
    public function getSubtotalInReaisAttribute(): string
    {
        return $this->formatCurrency($this->subtotal);
    }

    /**
     * Acessor: retorna o custo de frete formatado em reais (R$).
     *
     * @return string Valor de frete com separador decimal brasileiro.
     */
    public function getShippingCostInReaisAttribute(): string
    {
        return $this->formatCurrency($this->shipping_cost);
    }
}
