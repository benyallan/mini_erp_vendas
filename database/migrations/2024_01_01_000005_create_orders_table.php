<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('status');
            $table->integer('subtotal');
            $table->integer('shipping_cost')->default(0);
            $table->integer('total');
            $table->string('postal_code', 8);
            $table->text('address')->nullable();
            $table->foreignId('coupon_id')
                ->nullable()
                ->constrained('coupons')
                ->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('orders');
    }
};
