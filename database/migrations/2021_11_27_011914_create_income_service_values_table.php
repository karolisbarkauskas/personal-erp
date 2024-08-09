<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncomeServiceValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('income_service_values', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('income_id')->nullable();
            $table->unsignedBigInteger('service_id')->nullable();
            $table->double('amount');

            $table->foreign('income_id')
                ->references('id')
                ->on('incomes')
                ->onUpdate('NO ACTION')
                ->onDelete('SET NULL');

            $table->foreign('service_id')
                ->references('id')
                ->on('services')
                ->onUpdate('NO ACTION')
                ->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('income_service_values');
    }
}
