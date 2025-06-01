<form method="POST" action="/product">
    @csrf
    <input type="text" name="name" placeholder="Nome do produto" required>
    <input type="number" step="0.01" name="price" placeholder="Preço base" required>
    <h4>Variações:</h4>
    <div id="variations">
        <input type="text" name="variations[0][name]" placeholder="Nome variação">
        <input type="number" step="0.01" name="variations[0][price]" placeholder="Preço variação">
        <input type="number" name="variations[0][stock]" placeholder="Estoque inicial">
    </div>
    <button type="submit">Salvar Produto</button>
</form>
