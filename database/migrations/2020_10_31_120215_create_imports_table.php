<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('imports', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable();
            $table->string('number')->nullable();
            $table->string('transaction_number')->nullable();
            $table->date('date')->nullable();
            $table->string('payer')->nullable();
            $table->double('sum')->nullable();
            $table->string('purpose')->nullable();
            $table->string('credit_debit')->nullable();
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
        Schema::dropIfExists('imports');
    }
}
