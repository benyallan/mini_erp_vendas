@extends('layouts.app')

@section('content')
    <h4>Pedido #{{ $order->id }}</h4>
    <p><strong>Status:</strong> {{ $order->status }}</p>
    <p><strong>Endereço:</strong> {{ $order->address }}</p>
    <p><strong>CEP:</strong> {{ $order->postal_code }}</p>

    <table class="table mt-3">
        <thead>
            <tr>
                <th>Item</th>
                <th>Quantidade</th>
                <th>Unitário</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->variation->name }} ({{ $item->variation->size->value }})</td>
                    <td>{{ $item->quantity }}</td>
                    <td>R$ {{ $item->unit_price_in_reais }}</td>
                    <td>R$ {{ $item->total_in_reais }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p><strong>Subtotal:</strong> R$ {{ $order->subtotal_in_reais }}</p>
    <p><strong>Frete:</strong> R$ {{ $order->shipping_cost_in_reais }}</p>
    <p><strong>Total:</strong> <strong>R$ {{ $order->total_in_reais }}</strong></p>
@endsection
