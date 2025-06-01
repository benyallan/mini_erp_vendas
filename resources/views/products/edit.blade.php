@extends('layouts.app')

@section('content')
    <form method="POST" action="{{ route('products.update', $product) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Nome do Produto</label>
            <input type="text" class="form-control" name="name" value="{{ $product->name }}" required>
        </div>

        <div class="mb-3">
            <h5>Variações</h5>
            @foreach($product->variations as $i => $variation)
                <div class="row g-2 mb-2">
                    <div class="col">
                        <input type="text" name="variations[{{ $i }}][name]" class="form-control" value="{{ $variation->name }}" placeholder="Cor">
                    </div>
                    <div class="col">
                        <input type="number" name="variations[{{ $i }}][price]" class="form-control" value="{{ $variation->price }}" placeholder="Preço (centavos)">
                    </div>
                    <div class="col">
                        <input type="number" name="variations[{{ $i }}][stock]" class="form-control" value="{{ $variation->stock->quantity ?? 0 }}" placeholder="Estoque">
                    </div>
                </div>
            @endforeach
        </div>

        <button type="submit" class="btn btn-primary">Atualizar Produto</button>
    </form>
@endsection
