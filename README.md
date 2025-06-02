# Mini ERP - Controle de Pedidos, Produtos, Cupons e Estoque

Este é um projeto Laravel desenvolvido como parte de um teste técnico. A aplicação funciona como um mini ERP, permitindo o controle de produtos, variações, estoque, pedidos e cupons de desconto.

## Tecnologias Utilizadas

- **PHP 8.3+**
- **Laravel 12**
- **MySQL**
- **Blade (Bootstrap 5)**
- **Laravel Mailable (envio de e-mail)**
- **Enum PHP 8.1+**
- **Session para gerenciamento de carrinho**
- **Validação via FormRequest**
- **Webhook RESTful**
- **Testes com PHPUnit**

## Funcionalidades

### 📦 Produtos & Estoque

- Cadastro de produtos com variações (ex: Tamanho P, M, G).
- Controle de estoque por variação.
- Atualização e exclusão de produtos.

### 🛒 Carrinho

- Adição de variações ao carrinho.
- Validação de estoque.
- Cálculo automático de frete:
  - R$15,00 entre R$52,00 e R$166,59.
  - Grátis acima de R$200,00.
  - R$20,00 para demais valores.

### 🧾 Pedidos

- Finalização de pedido com endereço e CEP.
- Consulta de endereço via [ViaCEP](https://viacep.com.br).
- Cálculo de subtotal, frete, cupom e total.
- Envio de e-mail de confirmação do pedido.
- Enum para controle de status (pending, paid, cancelled).

### 💸 Cupons

- CRUD de cupons com regras de:
  - Valor mínimo de compra.
  - Data de validade.
- Aplicação de cupom no checkout com cálculo de desconto.

### 🔄 Webhook

- Endpoint `/api/webhook` (POST) para atualizar o status de um pedido.
- Se status for "cancelled", o pedido permanece mas seu status é atualizado.
- Validação por enum com retorno em JSON.

### ✅ Testes Automatizados

- Teste de fluxo completo de finalização de pedido.
- Teste de envio de webhook com status válido/inválido.
- Teste de regras de cupom e estoque.

## Instalação

```bash
git clone git@github.com:benyallan/mini_erp_vendas.git
cd mini_erp_vendas

composer install
cp .env.example .env
php artisan key:generate

# Configure banco de dados no .env

php artisan migrate
php artisan serve
```

## Rotas Importantes

| Recurso      | Caminho                  | Método |
|--------------|--------------------------|--------|
| Produtos     | `/`                      | GET    |
| Criar Produto| `/products/create`       | GET    |
| Checkout     | `/checkout`              | GET    |
| Finalizar    | `/checkout/finalize`     | POST   |
| Cupom (CRUD) | `/coupons`               | GET    |
| Webhook      | `/api/webhook`           | POST   |

## Exemplo Webhook

```bash
curl -X POST http://localhost/api/webhook \
     -H "Content-Type: application/json" \
     -H "Accept: application/json" \
     -d '{"id": 1, "status": "cancelled"}'
```

## Considerações Finais

Este projeto foi estruturado seguindo os princípios do MVC, com código limpo, boas práticas e foco em manutenibilidade e clareza. O objetivo foi resolver o desafio de forma prática e completa, contemplando as instruções e pontos adicionais.

---

### Autor

Beny Allan – [benyallan@gmail.com](mailto:benyallan@gmail.com)