<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAttachReportColumnToIncomeEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('income_emails', function (Blueprint $table) {
            $table->boolean('attach_report')->after('attach_debts')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('income_emails', function (Blueprint $table) {
            $table->dropColumn('attach_debts');
        });
    }
}
