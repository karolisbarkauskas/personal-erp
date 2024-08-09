<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTaskIdColumnToIncomeReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('income_reports', function (Blueprint $table) {
            $table->string('task_id')->after('name')->nullable();
            $table->string('task_link')->after('name')->nullable();
            $table->boolean('include')->after('name')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('income_reports', function (Blueprint $table) {
            $table->dropColumn('task_id', 'task_link', 'include');
        });
    }
}
