@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h4>Criar Cupom</h4>
        <a href="{{ route('coupons.index') }}" class="btn btn-secondary">Voltar para Lista de Cupons</a>
    </div>

    <form method="POST" action="{{ route('coupons.store') }}">
        @csrf

        <div class="mb-3">
            <label for="code" class="form-label">Código</label>
            <input type="text" name="code" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="discount" class="form-label">Desconto (em centavos)</label>
            <input type="number" name="discount" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="minimum_amount" class="form-label">Valor Mínimo (em centavos)</label>
            <input type="number" name="minimum_amount" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="expires_at" class="form-label">Validade</label>
            <input type="date" name="expires_at" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Salvar</button>
    </form>
@endsection