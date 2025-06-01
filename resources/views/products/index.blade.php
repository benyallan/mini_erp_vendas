@extends('layouts.app')

@section('content')
    <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">Novo Produto</a>
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
                                    {{ $variation->size->value ?? '' }} {{ $variation->name }} - R$ {{ $variation->price_in_reais }}
                                    (Estoque: {{ $variation->stock->quantity ?? 0 }})
                                </li>
                            @endforeach
                        </ul>
                    </td>
                    <td>
                        <a href="#" class="btn btn-sm btn-outline-primary">Editar</a>
                        <a href="#" class="btn btn-sm btn-outline-success">Comprar</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
