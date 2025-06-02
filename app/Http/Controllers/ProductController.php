<?php

namespace App\Http\Controllers;

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

    public function update(Request $request, Product $product)
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

    public function store(Request $request)
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

    public function addToCart(Request $request, CartService $cartService)
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

    public function finalizeOrder(Request $request, FinalizeOrderService $service)
    {
        try {
            $service->handle($request->all());

            return redirect()->route('products.index')->with('success', 'Pedido finalizado com sucesso.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Erro ao finalizar pedido: '.$e->getMessage());
        }
    }

    public function webhook(Request $request)
    {
        $data = $request->all();

        return response()->json(['status' => 'ok']);
    }
}
