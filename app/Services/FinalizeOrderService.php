<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Variation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class FinalizeOrderService
{
    public function handle(array $requestData): Order
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            throw new \Exception('Carrinho vazio.');
        }

        $subtotal = collect($cart)->sum(fn ($item) => $item['price'] * $item['quantity']);
        $shipping = $this->calculateShipping($subtotal);
        $total = $subtotal + $shipping;

        return DB::transaction(function () use ($cart, $requestData, $subtotal, $shipping, $total) {
            $order = Order::create([
                'subtotal' => $subtotal,
                'shipping_cost' => $shipping,
                'total' => $total,
                'postal_code' => $requestData['cep'],
                'address' => $requestData['address'],
            ]);

            foreach ($cart as $item) {
                $variation = Variation::with('stock')->findOrFail($item['variation_id']);

                if ($variation->stock->quantity < $item['quantity']) {
                    throw new \Exception("Estoque insuficiente para {$variation->name}.");
                }

                $variation->stock->decrement('quantity', $item['quantity']);

                $order->items()->create([
                    'variation_id' => $variation->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                ]);
            }

            Session::forget('cart');

            return $order;
        });
    }

    private function calculateShipping(int $subtotal): int
    {
        return match (true) {
            $subtotal > 20000 => 0,
            $subtotal >= 5200 && $subtotal <= 16659 => 1500,
            default => 2000,
        };
    }
}
