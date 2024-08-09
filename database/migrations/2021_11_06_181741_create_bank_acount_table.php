<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankAcountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('exp_cat_id');
            $table->string("iban");
            $table->timestamps();

            $table->foreign('exp_cat_id')
                ->references('id')
                ->on('expenses_categories')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bank_accounts');
    }
}
