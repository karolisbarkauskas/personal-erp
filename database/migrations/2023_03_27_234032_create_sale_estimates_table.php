<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleEstimatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_estimates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee')->nullable();
            $table->double('hourly_rate')->default(0);
            $table->double('hours_needed')->default(0);
            $table->double('total')->default(0);
            $table->double('hours_for_employee')->default(0);

            $table->foreign('employee')
                ->references('id')
                ->on('employees')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_estimates');
    }
}
