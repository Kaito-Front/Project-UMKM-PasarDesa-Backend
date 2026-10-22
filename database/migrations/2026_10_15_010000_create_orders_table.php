<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('product_name');
            $table->string('buyer_name');
            $table->string('phone');
            $table->decimal('total', 12, 2);
            $table->unsignedInteger('qty');
            $table->string('status')->default('diproses')->index();
            $table->string('shop_name');
            $table->string('kecamatan')->nullable();
            $table->text('address');
            $table->json('items');
            $table->string('shipping_method');
            $table->string('payment_method');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
