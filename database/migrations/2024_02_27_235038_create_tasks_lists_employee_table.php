<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksListsEmployeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_lists_employee', function (Blueprint $table) {
            $table->unsignedBigInteger('task_list_id');
            $table->unsignedBigInteger('employee_id');
            $table->float('hours')->default(0);

            $table->foreign('task_list_id')->references('id')->on('task_lists');
            $table->foreign('employee_id')->references('id')->on('employees');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('task_lists_employee');
    }
}
