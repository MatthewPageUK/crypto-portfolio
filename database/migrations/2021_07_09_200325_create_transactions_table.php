<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Transaction;

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
            $table->id();
            $table->unsignedBigInteger('token_id');
            $table->foreign('token_id')->references('id')->on('tokens')->onDelete('cascade');
            $table->unsignedDecimal('quantity', 36, 18);
            $table->unsignedDecimal('price', 36, 18);
            $table->enum('type', [Transaction::BUY, Transaction::SELL]);
            $table->dateTime('time')->useCurrent();
            $table->timestamps($precision = 0);
            $table->softDeletes($column = 'deleted_at');
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
