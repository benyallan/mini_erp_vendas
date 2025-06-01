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

    <div class="mt-3">
        <p><strong>Subtotal:</strong> R$ {{ number_format($subtotal / 100, 2, ',', '.') }}</p>
        <p><strong>Frete:</strong> R$ {{ number_format($shipping / 100, 2, ',', '.') }}</p>
        <p><strong>Total:</strong> <strong>R$ {{ number_format($total / 100, 2, ',', '.') }}</strong></p>
    </div>

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

    <script>
        document.querySelector('input[name="cep"]').addEventListener('blur', function () {
            const cep = this.value.replace(/\D/g, '');
            const addressField = document.querySelector('textarea[name="address"]');

            if (cep.length !== 8) {
                alert('CEP inválido. Deve conter 8 dígitos.');
                return;
            }

            fetch(`https://viacep.com.br/ws/${cep}/json/`)
                .then(response => response.json())
                .then(data => {
                    if (data.erro) {
                        alert('CEP não encontrado.');
                        addressField.value = '';
                    } else {
                        addressField.value = `${data.logradouro}, ${data.bairro}, ${data.localidade} - ${data.uf}`;
                    }
                })
                .catch(error => {
                    alert('Erro ao consultar o CEP.');
                    console.error(error);
                });
        });
    </script>
@endsection
