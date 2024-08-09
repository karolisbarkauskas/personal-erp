<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHourlyRatesToSaleEstimatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sale_estimates', function (Blueprint $table) {
            $table->float('hourly_rate_without_markup')->nullable()->after('hours_for_employee')->default(0);
            $table->float('hourly_rate_with_markup')->nullable()->after('hours_for_employee')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale_estimates', function (Blueprint $table) {
            $table->dropColumn('hourly_rate_without_markup');
            $table->dropColumn('hourly_rate_with_markup');
        });
    }
}
