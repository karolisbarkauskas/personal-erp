<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->integer('client')->unsigned();
            $table->integer('status')->unsigned();
            $table->integer('category')->unsigned();
            $table->date('issue_date');
            $table->date('income_date');
            $table->string('invoice_no')->nullable();
            $table->text('description');
            $table->float('amount');
            $table->float('vat_size');
            $table->float('vat_amount')->nullable();
            $table->float('total')->nullable();
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
        Schema::dropIfExists('incomes');
    }
}
