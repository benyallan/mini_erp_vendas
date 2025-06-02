@extends('layouts.app')

@section('content')
    <h4>Cupons</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('coupons.create') }}" class="btn btn-primary">Novo Cupom</a>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Voltar para Produtos</a>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Código</th>
                <th>Desconto</th>
                <th>Mínimo</th>
                <th>Validade</th>
            </tr>
        </thead>
        <tbody>
            @foreach($coupons as $coupon)
                <tr>
                    <td>{{ $coupon->code }}</td>
                    <td>R$ {{ number_format($coupon->discount / 100, 2, ',', '.') }}</td>
                    <td>R$ {{ number_format($coupon->minimum_amount / 100, 2, ',', '.') }}</td>
                    <td>{{ \Carbon\Carbon::parse($coupon->expires_at)->format('d/m/Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection