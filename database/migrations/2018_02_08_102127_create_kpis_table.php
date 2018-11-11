<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKpisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kpis', function (Blueprint $table) {
            $table->increments('id');
            $table->char('name',100);
            $table->string('queryWord',500)->nullable();
            $table->string('calculationQuery',1000)->nullable();
            $table->string('calculationQueryForGroup',1000)->nullable();
            $table->json('calculationParameters')->nullable();
            $table->string('toolTipTemplate',1000)->nullable();
            $table->string('toolTipNumbers',1000)->nullable();
            $table->char('color',8)->nullable();
            $table->boolean('showOnMainScreen')->nullable();
            $table->integer('decimalPlaces')->nullable();
            $table->integer('viewMultiplier')->nullable();
            $table->char('preSign',2)->nullable();
            $table->char('postSign',2)->nullable();
            $table->string('link', 100)->nullable();
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
        Schema::dropIfExists('kpis');
    }
}
