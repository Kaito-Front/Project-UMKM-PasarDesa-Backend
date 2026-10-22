<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->string('icon')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('shops', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->string('owner');
            $table->string('category');
            $table->string('category_slug')->index();
            $table->string('kecamatan');
            $table->text('address')->nullable();
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();
            $table->string('phone')->nullable();
            $table->string('whatsapp')->nullable();
            $table->decimal('rating', 3, 1)->default(0);
            $table->unsignedInteger('review_count')->default(0);
            $table->string('join_date')->nullable();
            $table->string('operating_hours')->nullable();
            $table->text('description')->nullable();
            $table->text('avatar')->nullable();
            $table->text('banner')->nullable();
            $table->string('status')->default('aktif');
            $table->boolean('verified')->default(false);
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->decimal('price', 12, 2);
            $table->decimal('original_price', 12, 2)->nullable();
            $table->string('category_id');
            $table->string('shop_id');
            $table->string('kecamatan')->nullable();
            $table->unsignedInteger('stock')->default(0);
            $table->decimal('rating', 3, 1)->default(0);
            $table->unsignedInteger('sold')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_popular')->default(false);
            $table->boolean('is_new')->default(false);
            $table->boolean('is_hidden')->default(false);
            $table->json('images')->nullable();
            $table->text('description')->nullable();
            $table->json('specifications')->nullable();
            $table->json('reviews')->nullable();
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->restrictOnDelete();
            $table->foreign('shop_id')->references('id')->on('shops')->cascadeOnDelete();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('products');
        Schema::dropIfExists('shops');
        Schema::dropIfExists('categories');
    }
};
