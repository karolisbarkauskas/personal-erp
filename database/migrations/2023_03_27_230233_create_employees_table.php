<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->double('salary_with_vat')->nullable();
            $table->double('sellable_hours_per_day')->nullable();
            $table->double('hourly_rate_without_markup')->nullable();
            $table->double('hourly_rate_with_markup')->nullable();
            $table->double('hourly_rate_sellable')->nullable();
            $table->date('employment_start')->nullable();
            $table->date('employment_end')->nullable();
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
        Schema::dropIfExists('employees');
    }
}
