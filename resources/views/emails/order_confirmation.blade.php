<h2>Pedido Confirmado</h2>

<p>Obrigado por sua compra!</p>

<p><strong>Endereço:</strong> {{ $order->address }}</p>
<p><strong>CEP:</strong> {{ $order->postal_code }}</p>

<p><strong>Itens do Pedido:</strong></p>
<ul>
    @foreach($order->items as $item)
        <li>{{ $item->variation->product->name }} {{ $item->variation->name }} - {{ $item->quantity }}x R$ {{ number_format($item->unit_price / 100, 2, ',', '.') }}</li>
    @endforeach
</ul>

<p><strong>Total:</strong> R$ {{ number_format($order->total / 100, 2, ',', '.') }}</p>
