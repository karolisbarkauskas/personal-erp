<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSendAtColumnToIncomeEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('income_emails', function (Blueprint $table) {
            $table->dateTime('send_at')->nullable()->after('content');
            $table->string('locale')->default('lt')->after('id');
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
            $table->dropColumn('send_at');
            $table->dropColumn('locale');
        });
    }
}
