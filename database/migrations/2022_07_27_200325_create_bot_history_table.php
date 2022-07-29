<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBotHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bot_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bot_id');
            $table->foreign('bot_id')->references('id')->on('bots')->onDelete('cascade');
            $table->unsignedDecimal('target_price', 36, 18);
            $table->unsignedDecimal('stop_loss', 36, 18);
            $table->unsignedDecimal('price', 36, 18);
            $table->string('note');
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
        Schema::dropIfExists('bot_history');
    }
}
