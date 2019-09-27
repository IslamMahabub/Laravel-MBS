<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->bigIncrements('id');
            $table->bigInteger('account_id')->unsigned();
            $table->foreign('account_id')->references('id')->on('account')->onDelete('cascade');
            $table->enum('transaction_type', ['D', 'W', 'T']);
            $table->float('amount', 8, 2);
            $table->enum('transaction_action', ['D', 'C']);
            $table->bigInteger('transfer_id')->nullable();
            $table->foreign('transfer_id')->references('id')->on('transfer')->onDelete('cascade');
            $table->dateTime('transaction_date');
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