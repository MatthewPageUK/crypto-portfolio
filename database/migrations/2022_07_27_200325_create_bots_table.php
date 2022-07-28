<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bots', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('token_id');
            $table->foreign('token_id')->references('id')->on('tokens')->onDelete('cascade');
            $table->string('name', 100);
            $table->string('direction', 10);
            $table->unsignedDecimal('quantity', 36, 18);
            $table->unsignedDecimal('price', 36, 18);
            $table->unsignedDecimal('profit', 8, 2);
            $table->unsignedDecimal('loss', 8, 2);
            $table->string('status', 25);
            $table->dateTime('started')->nullable();
            $table->dateTime('stopped')->nullable();

            $table->timestamps();
            $table->softDeletes();
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
