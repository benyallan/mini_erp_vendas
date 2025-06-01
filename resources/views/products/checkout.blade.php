@extends('layouts.app')

@section('content')
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <h4>Carrinho</h4>
    <table class="table">
        <thead>
            <tr>
                <th>Variação</th>
                <th>Quantidade</th>
                <th>Preço unitário</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cart as $item)
                <tr>
                    <td>{{ $item['name'] ?? '' }}</td>
                    <td>{{ $item['quantity'] }}</td>
                    <td>R$ {{ number_format($item['price'] / 100, 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <form method="POST" action="{{ route('checkout.finalize') }}">
        @csrf
        <div class="mb-3">
            <label for="cep" class="form-label">CEP</label>
            <input type="text" name="cep" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Endereço</label>
            <textarea name="address" class="form-control" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Finalizar Pedido</button>
    </form>
@endsection
