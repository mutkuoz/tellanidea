<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMonthlyDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monthly_datas', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('customer_id')->index();
            $table->integer('product_id')->index();
            $table->integer('yearMonth')->index();
            $table->double('revenues',18,2)->nullable();
            $table->tinyInteger('ownership')->nullable();
            $table->double('volume_eop',18,2)->nullable();
            $table->double('volume_avg',18,2)->nullable();
            $table->double('turnover',18,2)->nullable();
            $table->double('rwa_eop',18,2)->nullable();
            $table->double('rwa_avg',18,2)->nullable();
            $table->double('rsn_bank',18,2)->nullable();
            $table->double('rsn_market',18,2)->nullable();
            $table->double('limit',24,6)->nullable();
            $table->timestamps();
            $table->index(['customer_id', 'product_id','yearMonth']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('monthly_datas');
    }
}
