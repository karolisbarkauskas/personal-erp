<?php

namespace App\Http\Controllers;

use App\Client;
use App\Income;
use App\Label;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ClientTableController extends Controller
{
    public function index(Request $request)
    {
        $result = Client::all();
        $table = DataTables::of($result);
        $table->addColumn('income_this_year', function (Client $client) {
            return $client->getTotalIncomeThisYear();
        });
        $table->addColumn('income_this_year_display', function (Client $client) {
            return Label::formatPrice($client->getTotalIncomeThisYear());
        });
        $table->addColumn('total_income', function (Client $client) {
            return $client->getTotalIncome();
        });
        $table->addColumn('total_income_display', function (Client $client) {
            return Label::formatPrice($client->getTotalIncome());
        });
        $table->addColumn('debt', function (Client $client) {
            return $client->getTotalDebt();
        });
        $table->addColumn('debt_display', function (Client $client) {
            return Label::formatPrice($client->getTotalDebt());
        });
        $table->addColumn('invoices', function (Client $client) {
            return $client->getInvoiceCount();
        });
        $table->addColumn('avg_invoice', function (Client $client) {
            return $client->getAverageInvoice();
        });
        $table->addColumn('avg_invoice_display', function (Client $client) {
            return Label::formatPrice($client->getAverageInvoice());
        });
        $table->addColumn('avg_time', function (Client $client) {
            return 'disabled'; // $client->getAverageTime();
        });

        return $table->make(true);
    }
}
