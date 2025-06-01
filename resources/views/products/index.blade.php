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

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('products.create') }}" class="btn btn-primary">Novo Produto</a>
        <a href="{{ route('checkout') }}" class="btn btn-outline-secondary">Ver Carrinho</a>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Produto</th>
                <th>Variações</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>
                        <ul>
                            @foreach($product->variations as $variation)
                                <li>
                                    {{ $product->name }} {{ $variation->name }} - R$ {{ $variation->price_in_reais }}
                                    (Estoque: {{ $variation->stock->quantity ?? 0 }})
                                </li>
                            @endforeach
                        </ul>
                    </td>
                    <td>
                        <form method="POST" action="{{ route('cart.add') }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="variation_id" value="{{ $variation->id }}">
                            <input type="number" name="quantity" value="1" min="1" class="form-control d-inline w-auto">
                            <button type="submit" class="btn btn-sm btn-success">Comprar</button>
                        </form>
                        <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                        <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir?')" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Excluir</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
