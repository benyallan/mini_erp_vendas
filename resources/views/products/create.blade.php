@extends('layouts.app')

@section('content')
    <form method="POST" action="{{ route('products.store') }}">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nome do Produto</label>
            <input type="text" class="form-control" name="name" required>
        </div>

        <div class="mb-3">
            <h5>Variações</h5>
            <div id="variations">
                <div class="row g-2 mb-2">
                    <div class="col">
                        <input type="text" name="variations[0][name]" class="form-control" placeholder="Cor e tamanho (ex: Vermelho P)">
                    </div>
                    <div class="col">
                        <input type="number" name="variations[0][price]" class="form-control" placeholder="Preço (centavos)">
                    </div>
                    <div class="col">
                        <input type="number" name="variations[0][stock]" class="form-control" placeholder="Estoque">
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-success">Salvar Produto</button>
    </form>
@endsection
