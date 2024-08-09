<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddResourcePlanColumnsToWeekTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('week_tasks', function (Blueprint $table) {
            $table->float('resource_time')->after('employee_hours')->default(0);
            $table->float('resource_time_employee')->after('employee_hours')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('week_tasks', function (Blueprint $table) {
            $table->dropColumn('resource_time');
            $table->dropColumn('resource_time_employee');
        });
    }
}
