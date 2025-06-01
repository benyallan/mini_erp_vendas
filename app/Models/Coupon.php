<?php

namespace App\Models;

use App\Traits\FormatsCurrency;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Coupon extends Model
{
    /** @use HasFactory<\Database\Factories\CouponFactory> */
    use HasFactory, FormatsCurrency;

    /**
     * Os atributos que podem ser preenchidos em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'discount',
        'minimum_amount',
        'expires_at',
    ];

    /**
     * Verifica se o cupom está expirado com base na data atual.
     *
     * @return bool True se o cupom estiver expirado, false caso contrário.
     */
    public function isExpired(): bool
    {
        return Carbon::parse($this->expires_at)->isPast();
    }

    /**
     * Determina se o cupom é válido para um subtotal fornecido (em centavos).
     *
     * @param  int  $subtotal  Subtotal do carrinho em centavos.
     * @return bool True se o cupom for válido para o subtotal e ainda estiver ativo.
     */
    public function isValidFor(int $subtotal): bool
    {
        return ! $this->isExpired() && $subtotal >= $this->minimum_amount;
    }

    /**
     * Retorna o valor do desconto formatado em reais (R$).
     *
     * @return string Desconto formatado com separadores decimais brasileiros.
     */
    public function getDiscountInReaisAttribute(): string
    {
        return $this->formatCurrency($this->discount);
    }

    /**
     * Retorna o valor mínimo de aplicação formatado em reais (R$).
     *
     * @return string Valor mínimo formatado com separadores decimais brasileiros.
     */
    public function getMinimumAmountInReaisAttribute(): string
    {
        return $this->formatCurrency($this->minimum_amount);
    }

    /**
     * Relacionamento: retorna os pedidos que utilizaram este cupom.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
