<?php

namespace App\Services;

use App\Models\Coupon;
use Illuminate\Support\Facades\Session;

class CartService
{
    /**
     * Cache interno do cupom, evita múltiplas consultas na mesma request.
     *
     * @var Coupon|null
     */
    protected ?Coupon $cachedCoupon = null;

    /**
     * Recupera os itens do carrinho armazenados na sessão.
     *
     * @return array<int, array<string, mixed>>
     */
    public function get(): array
    {
        return Session::get('cart', []);
    }

    /**
     * Salva os itens do carrinho na sessão.
     *
     * @param array<int, array<string, mixed>> $cart
     * @return void
     */
    public function put(array $cart): void
    {
        Session::put('cart', $cart);
    }

    /**
     * Remove os itens do carrinho da sessão.
     *
     * @return void
     */
    public function clear(): void
    {
        Session::forget('cart');
    }

    /**
     * Calcula o subtotal do carrinho.
     *
     * @param array<int, array<string, mixed>> $cart
     * @return int
     */
    public function subtotal(array $cart): int
    {
        return collect($cart)->sum(fn ($item) => $item['price'] * $item['quantity']);
    }

    /**
     * Retorna o valor do frete baseado no subtotal.
     *
     * @param int $subtotal
     * @return int
     */
    public function shipping(int $subtotal): int
    {
        return match (true) {
            $subtotal > 20000 => 0,
            $subtotal >= 5200 && $subtotal <= 16659 => 1500,
            default => 2000,
        };
    }

    /**
     * Calcula o total do pedido (subtotal + frete).
     *
     * @param array<int, array<string, mixed>> $cart
     * @return int
     */
    public function total(array $cart): int
    {
        $subtotal = $this->subtotal($cart);
        return $subtotal + $this->shipping($subtotal);
    }

    /**
     * Recupera o cupom aplicado na sessão, se existir.
     *
     * @return Coupon|null
     */
    public function getCoupon(): ?Coupon
    {
        if ($this->cachedCoupon !== null) {
            return $this->cachedCoupon;
        }

        $code = Session::get('coupon_code');

        return $this->cachedCoupon = $code
            ? Coupon::where('code', $code)->first()
            : null;
    }

    /**
     * Calcula o valor de desconto baseado no cupom e subtotal.
     *
     * @param Coupon|null $coupon
     * @param int $subtotal
     * @return int
     */
    public function discount(?Coupon $coupon, int $subtotal): int
    {
        return ($coupon && $coupon->isValidFor($subtotal))
            ? $coupon->discount
            : 0;
    }
}
