<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Http\Requests\AddToCartRequest;
use App\Http\Requests\FinalizeOrderRequest;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\WebhookRequest;
use App\Models\Order;
use App\Models\Product;
use App\Models\Variation;
use App\Services\CartService;
use App\Services\FinalizeOrderService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('variations.stock')->get();

        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(ProductRequest $request, Product $product)
    {
        $product->update($request->only('name', 'price'));
        $product->variations()->delete();

        foreach ($request->variations as $variationData) {
            $variation = $product->variations()->create([
                'name' => $variationData['name'],
                'price' => $variationData['price'],
            ]);
            $variation->stock()->create(['quantity' => $variationData['stock']]);
        }

        return redirect('/');
    }

    public function store(ProductRequest $request)
    {
        $product = Product::create($request->only('name', 'price'));
        foreach ($request->variations as $variationData) {
            $variation = $product->variations()->create([
                'name' => $variationData['name'],
                'price' => $variationData['price'],
            ]);
            $variation->stock()->create(['quantity' => $variationData['stock']]);
        }

        return redirect('/');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect('/');
    }

    public function addToCart(AddToCartRequest $request, CartService $cartService)
    {
        $variation = Variation::with('stock')->findOrFail($request->variation_id);
        $quantity = (int) $request->quantity;

        if (! $variation->canFulfill($quantity)) {
            return redirect()->back()->with('error', 'Estoque insuficiente.');
        }

        $cart = $cartService->get();

        $cart[] = [
            'variation_id' => $variation->id,
            'name' => $variation->name,
            'price' => $variation->price,
            'quantity' => $quantity,
        ];

        $cartService->put($cart);

        return redirect()->back()->with('success', 'Item adicionado ao carrinho.');
    }

    public function checkout(CartService $cartService)
    {
        $cart = $cartService->get();
        $subtotal = $cartService->subtotal($cart);
        $shipping = $cartService->shipping($subtotal);
        $coupon = $cartService->getCoupon();
        $discount = $cartService->discount($coupon, $subtotal);
        $total = $subtotal + $shipping - $discount;

        return view('products.checkout', compact('cart', 'subtotal', 'shipping', 'total', 'discount', 'coupon'));
    }

    public function finalizeOrder(FinalizeOrderRequest $request, FinalizeOrderService $service)
    {
        try {
            $service->handle($request->all());

            return redirect()->route('products.index')->with('success', 'Pedido finalizado com sucesso.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Erro ao finalizar pedido: '.$e->getMessage());
        }
    }

    public function webhook(WebhookRequest $request)
    {
        $order = Order::find($request->id);

        $order->update([
            'status' => OrderStatus::from($request->status),
        ]);

        return response()->json(['message' => 'Status do pedido atualizado.']);
    }
}
