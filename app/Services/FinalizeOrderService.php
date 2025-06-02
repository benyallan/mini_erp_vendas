<?php

namespace App\Services;

use App\Mail\OrderConfirmationMail;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Variation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Mail;

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

        // Aplicar cupom (se informado)
        $coupon = null;
        $discount = 0;

        if (! empty($requestData['coupon_code'])) {
            $coupon = Coupon::where('code', $requestData['coupon_code'])->first();

            $discount = $coupon->discount;
        }

        $total = $subtotal + $shipping - $discount;

        return DB::transaction(function () use (
            $cart,
            $requestData,
            $subtotal,
            $shipping,
            $total,
            $coupon
        ) {
            $order = Order::create([
                'subtotal' => $subtotal,
                'shipping_cost' => $shipping,
                'total' => $total,
                'postal_code' => $requestData['cep'],
                'address' => $requestData['address'],
                'coupon_id' => $coupon?->id,
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

            Mail::to('cliente@email.com')->send(new OrderConfirmationMail($order));

            Session::forget('cart');
            Session::forget('coupon_code');

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
