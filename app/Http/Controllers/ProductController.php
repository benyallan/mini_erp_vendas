<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

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

    public function addToCart(Request $request)
    {
        $cart = Session::get('cart', []);
        $cart[] = $request->all();
        Session::put('cart', $cart);

        return redirect('/checkout');
    }

    public function checkout()
    {
        $cart = Session::get('cart', []);

        return view('products.checkout', compact('cart'));
    }

    public function finalizeOrder(Request $request)
    {
        return redirect('/')->with('success', 'Pedido realizado com sucesso!');
    }

    public function webhook(Request $request)
    {
        $data = $request->all();

        return response()->json(['status' => 'ok']);
    }
}
