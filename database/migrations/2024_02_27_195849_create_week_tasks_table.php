<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeekTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('week_tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('task_list_id');
            $table->unsignedBigInteger('client_id')->nullable();
            $table->string('link')->nullable();
            $table->string('name')->nullable();
            $table->longText('remarks')->nullable();
            $table->float('sold_to_client')->nullable();
            $table->float('employee_hours')->nullable();
            $table->float('real_work_hours')->nullable();
            $table->boolean('is_open')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('task_list_id')->references('id')->on('task_lists');
            $table->foreign('client_id')->references('id')->on('clients');
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
        Schema::dropIfExists('week_tasks');
    }
}
