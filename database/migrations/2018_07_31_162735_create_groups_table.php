<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupsTable extends Migration
{
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('name', 120)->nullable();
            $table->double('kpi_1',24,6)->nullable();
            $table->double('kpi_2',24, 6)->nullable();
            $table->double('kpi_3',24,6)->nullable();
            $table->double('kpi_4',24, 6)->nullable();
            $table->double('kpi_5',24, 6)->nullable();
            $table->double('kpi_6',24,6)->nullable();
            $table->double('kpi_7',24, 6)->nullable();
            $table->double('kpi_8',24,6)->nullable();
            $table->double('kpi_9',24, 6)->nullable();
            $table->double('kpi_10',24,6)->nullable();
            $table->double('kpi_11',24,6)->nullable();
            $table->double('kpi_12',24,6)->nullable();
            $table->double('kpi_13',24,6)->nullable();
            $table->double('kpi_14',24,6)->nullable();
            $table->double('kpi_15',24,6)->nullable();
            $table->double('kpi_16',24,6)->nullable();
            $table->double('kpi_17',24,6)->nullable();
            $table->double('kpi_18',24,6)->nullable();
            $table->double('kpi_19',24,6)->nullable();
            $table->double('kpi_20',24,6)->nullable();
            $table->double('kpi_21',24,6)->nullable();
            $table->double('kpi_22',24,6)->nullable();
            $table->double('kpi_23',24,6)->nullable();
            $table->double('kpi_24',24,6)->nullable();
            $table->double('kpi_25',24,6)->nullable();
            $table->double('kpi_26',24,6)->nullable();
            $table->double('kpi_27',24,6)->nullable();
            $table->double('kpi_28',24,6)->nullable();
            $table->double('kpi_29',24,6)->nullable();
            $table->double('kpi_30',24,6)->nullable();
            $table->double('kpi_31',24,6)->nullable();
            $table->double('kpi_32',24,6)->nullable();
            $table->double('kpi_33',24,6)->nullable();
            $table->double('kpi_34',24,6)->nullable();
            $table->double('kpi_35',24,6)->nullable();
            $table->double('kpi_36',24,6)->nullable();
            $table->double('kpi_37',24,6)->nullable();
            $table->double('kpi_38',24,6)->nullable();
            $table->double('kpi_39',24,6)->nullable();
            $table->double('kpi_40',24,6)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('groups');
    }
}
