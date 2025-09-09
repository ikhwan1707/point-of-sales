<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('transaction_id');
            $table->string('transaction_code')->unique();
            $table->foreignId('customer_id')->references('customer_id')->on('customers');
            $table->foreignId('user_id')->references('user_id')->on('users');
            $table->decimal('total_amount', 15, 2); 
            $table->decimal('paid_amount', 15, 2); 
            $table->decimal('change_amount', 15, 2);
            $table->enum('payment_method', ['cash', 'transfer']);
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');
            $table->timestamp('transaction_date');
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
        Schema::dropIfExists('transactions');
    }
}