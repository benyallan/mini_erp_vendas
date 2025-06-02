<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Services\CartService;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        return view('coupons.index', ['coupons' => Coupon::all()]);
    }

    public function create()
    {
        return view('coupons.create');
    }

    public function store(Request $request)
    {
        Coupon::create($request->only('code', 'discount', 'minimum_amount', 'expires_at'));

        return redirect()->route('coupons.index')->with('success', 'Cupom criado.');
    }

    public function applyCoupon(Request $request, CartService $cartService)
    {
        $code = $request->input('coupon_code');
        $coupon = Coupon::where('code', $code)->first();

        if (! $coupon || $coupon->isExpired()) {
            return redirect()->route('checkout')->with('coupon_error', 'Cupom inválido ou expirado.');
        }

        if (! $coupon->isValidFor($cartService->subtotal($cartService->get()))) {
            return redirect()->route('checkout')->with('coupon_error', 'Cupom não atende aos requisitos mínimos.');
        }

        session(['coupon_code' => $coupon->code]);

        return redirect()->route('checkout')->with('success', 'Cupom aplicado com sucesso.');
    }
}
