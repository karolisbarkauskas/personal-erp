<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncomeEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('income_emails', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('income_id')->nullable();
            $table->unsignedBigInteger('user_id');

            $table->boolean('attach_debts')->default(false);
            $table->text('subject');
            $table->json('receivers');
            $table->longText('content');

            $table->foreign('income_id')
                ->references('id')
                ->on('incomes')
                ->onUpdate('NO ACTION')
                ->onDelete('SET NULL');

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
        Schema::dropIfExists('income_emails');
    }
}
