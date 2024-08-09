<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExpenseIdColumnToRecurringExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recurring_incomes', function (Blueprint $table) {
            $table->unsignedBigInteger('expense_id')->after('period')->nullable();
            $table->foreign('expense_id')->references('id')->on('recurring_expenses')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recurring_incomes', function (Blueprint $table) {
            $table->dropForeign('recurring_incomes_expense_id_foreign');
            $table->dropColumn('expense_id');
        });
    }
}
