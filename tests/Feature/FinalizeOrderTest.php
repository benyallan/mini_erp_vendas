<?php

namespace Tests\Feature;

use App\Mail\OrderConfirmationMail;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class FinalizeOrderTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function usuario_pode_finalizar_um_pedido_com_variacoes_validas()
    {
        Mail::fake();

        $product = Product::factory()->create();
        $variation = $product->variations()->create([
            'name' => 'M',
            'price' => 1000,
        ]);
        $variation->stock()->create(['quantity' => 5]);

        session()->put('cart', [
            [
                'variation_id' => $variation->id,
                'name' => $variation->name,
                'price' => $variation->price,
                'quantity' => 2,
            ],
        ]);

        $response = $this->post(route('checkout.finalize'), [
            'cep' => '01001000',
            'address' => 'Rua Teste, Centro',
        ]);

        $response->assertRedirect('/');
        $this->assertDatabaseHas('orders', [
            'subtotal' => 2000,
            'total' => 4000, // 2000 + 2000 (frete)
        ]);

        // Confirma que o e-mail foi enviado com a classe correta
        Mail::assertSent(OrderConfirmationMail::class, function ($mail) {
            return $mail->hasTo('cliente@email.com'); // confere destino
        });
    }
}
