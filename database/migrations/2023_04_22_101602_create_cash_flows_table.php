<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashFlowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_flows', function (Blueprint $table) {
            $table->id();
            $table->date('income_date');
            $table->tinyInteger('type');
            $table->unsignedBigInteger('income_id')->nullable();
            $table->string('flow_name');
            $table->float('initial');
            $table->float('real')->nullable();
            $table->boolean('paid');

            $table->foreign('income_id')->references('id')->on('incomes');
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
        Schema::dropIfExists('cash_flows');
    }
}
