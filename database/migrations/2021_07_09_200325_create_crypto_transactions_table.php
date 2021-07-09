<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCryptoTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crypto_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('crypto_token_id');
            $table->foreign('crypto_token_id')->references('id')->on('crypto_tokens')->onDelete('cascade');
            $table->float('quantity', 20, 10);
            $table->float('price', 20, 10);
            $table->enum('type', ['buy', 'sell']);
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
        Schema::dropIfExists('crypto_transactions');
    }
}
