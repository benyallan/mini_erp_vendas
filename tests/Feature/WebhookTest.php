<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class WebhookTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function status_is_updated_when_valid_enum_is_sent()
    {
        $order = Order::factory()->create(['status' => OrderStatus::Pending]);

        $response = $this->postJson('/api/webhook', [
            'id' => $order->id,
            'status' => 'paid',
        ]);

        $response->assertOk()
            ->assertJson(['message' => 'Status do pedido atualizado.']);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => OrderStatus::Paid->value,
        ]);
    }

    #[Test]
    public function status_update_fails_with_invalid_enum()
    {
        $order = Order::factory()->create(['status' => OrderStatus::Pending]);

        $response = $this->postJson('/api/webhook', [
            'id' => $order->id,
            'status' => 'cancelado',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['status']);
    }
}
