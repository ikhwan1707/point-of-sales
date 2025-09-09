<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('product_id');
            $table->foreignId('categorie_id')->references('categorie_id')->on('categories');
            $table->string('name'); 
            $table->text('description')->nullable();
            $table->decimal('price', 15, 2);
            $table->integer('stock');
            $table->string('image')->nullable(); 
            $table->enum('status', ['available', 'unavailable'])->default('available'); 
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}