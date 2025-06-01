<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Variation;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;

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

    public function addToCart(Request $request)
    {
        $variation = Variation::with('stock')->findOrFail($request->variation_id);
        $quantity = (int) $request->quantity;

        if ($variation->stock->quantity < $quantity) {
            return redirect()->back()->with('error', 'Estoque insuficiente.');
        }

        $cart = session()->get('cart', []);

        $cart[] = [
            'variation_id' => $variation->id,
            'name' => $variation->name,
            'price' => $variation->price,
            'quantity' => $quantity,
        ];

        session(['cart' => $cart]);

        return redirect()->back()->with('success', 'Item adicionado ao carrinho.');
    }

    public function checkout()
    {
        $cart = session('cart', []);

        $subtotal = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        $shipping = match (true) {
            $subtotal > 20000 => 0,
            $subtotal >= 5200 && $subtotal <= 16659 => 1500,
            default => 2000,
        };

        $total = $subtotal + $shipping;

        return view('products.checkout', compact('cart', 'subtotal', 'shipping', 'total'));
    }

    public function finalizeOrder(Request $request)
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->back()->with('error', 'O carrinho está vazio.');
        }

        $subtotal = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        $shipping = match (true) {
            $subtotal > 20000 => 0,
            $subtotal >= 5200 && $subtotal <= 16659 => 1500,
            default => 2000,
        };

        $total = $subtotal + $shipping;

        DB::beginTransaction();

        try {
            $order = Order::create([
                'subtotal' => $subtotal,
                'shipping_cost' => $shipping,
                'total' => $total,
                'postal_code' => $request->cep,
                'address' => $request->address,
            ]);

            foreach ($cart as $item) {
                $variation = Variation::with('stock')->findOrFail($item['variation_id']);

                // Verificação final de estoque
                if ($variation->stock->quantity < $item['quantity']) {
                    throw new \Exception("Estoque insuficiente para esse produto {$variation->name}.");
                }

                // Abate do estoque
                $variation->stock->decrement('quantity', $item['quantity']);

                // Cria item do pedido
                $order->items()->create([
                    'variation_id' => $variation->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                ]);
            }

            DB::commit();
            session()->forget('cart');

            return redirect()->route('products.index', $order)->with('success', 'Pedido finalizado com sucesso.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erro ao finalizar pedido: ' . $e->getMessage());
        }
    }

    public function webhook(Request $request)
    {
        $data = $request->all();

        return response()->json(['status' => 'ok']);
    }
}
