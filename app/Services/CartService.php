<?php

namespace App\Services;

class CartService
{
    public function get(): array
    {
        return session('cart', []);
    }

    public function put(array $cart): void
    {
        session(['cart' => $cart]);
    }

    public function clear(): void
    {
        session()->forget('cart');
    }

    public function subtotal(array $cart): int
    {
        return collect($cart)->sum(fn ($item) => $item['price'] * $item['quantity']);
    }

    public function shipping(int $subtotal): int
    {
        return match (true) {
            $subtotal > 20000 => 0,
            $subtotal >= 5200 && $subtotal <= 16659 => 1500,
            default => 2000,
        };
    }

    public function total(array $cart): int
    {
        $subtotal = $this->subtotal($cart);

        return $subtotal + $this->shipping($subtotal);
    }
}
