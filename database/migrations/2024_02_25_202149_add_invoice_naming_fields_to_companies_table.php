<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInvoiceNamingFieldsToCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('invoice')->nullable()->after('invoice_name');
            $table->string('credit')->nullable()->after('invoice_name');
            $table->string('proforma')->nullable()->after('invoice_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('invoice');
            $table->dropColumn('credit');
            $table->dropColumn('proforma');
        });
    }
}
