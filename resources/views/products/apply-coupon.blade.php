@extends('layouts.app')

@section('content')
    <form method="POST" action="{{ route('cart.applyCoupon') }}">
        @csrf
        <div class="mb-3">
            <label for="code" class="form-label">Código do Cupom</label>
            <input type="text" class="form-control" name="code" placeholder="Digite o cupom">
        </div>
        <button type="submit" class="btn btn-success">Aplicar Cupom</button>
    </form>
@endsection
