<?php

namespace App\Http\Controllers;

use App\Income;
use App\Label;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class IncomeTableController extends Controller
{
    public function index(Request $request)
    {
        $result = Income::with(['incomeClient'])
            ->whereNotNull('invoice_no')
            ->orderBy('invoice_no', 'desc');

        $table = DataTables::of($result);
        $table->addColumn('client', function (Income $income) {
          return $income->incomeClient->name;
        });
        $table->addColumn('issue_date', function (Income $income) {
          return $income->issue_date;
        });
        $table->addColumn('sale', function (Income $income) {
          return $income->sale ?  $income->sale->name : '';
        });
        $table->addColumn('status', function (Income $income) {
          return !empty($income->incomeStatus) ? $income->incomeStatus->name : '';
        });
        $table->addColumn('statusClass', function (Income $income) {
          return $income->getStatusClass();
        });
        $table->addColumn('formated_amount', function (Income $income) {
          return Label::formatPrice($income->amount);
        });
        $table->addColumn('amount', function (Income $income) {
          return $income->amount;
        });
        $table->addColumn('number', function (Income $income) {
          return $income->invoice_no ? $income->invoice_no : '';
        });

        $table->addColumn('avg_amount', function (Income $income) {
          return $income->incomeClient->getAverageInvoice();
        });

        $table->addColumn('avg_class', function (Income $income) {
          return $income->incomeClient->getAverageInvoice() > $income->amount ? "danger" : "success";
        });
        return $this->calculateTotal($table->make(true));
    }

    public function short(Request $request)
    {
        $result = Income::with(['incomeClient'])
            ->whereNull('invoice_no')
            ->orderBy('invoice_no', 'desc');

        $table = DataTables::of($result);
        $table->addColumn('client', function (Income $income) {
            return $income->incomeClient->name;
        });
        $table->addColumn('issue_date', function (Income $income) {
            return $income->issue_date;
        });
        $table->addColumn('sale', function (Income $income) {
            return $income->sale ? $income->sale->name : '';
        });
        $table->addColumn('status', function (Income $income) {
            return !empty($income->incomeStatus) ? $income->incomeStatus->name : '';
        });
        $table->addColumn('statusClass', function (Income $income) {
            return $income->getStatusClass();
        });
        $table->addColumn('formated_amount', function (Income $income) {
            return Label::formatPrice($income->amount);
        });
        $table->addColumn('amount', function (Income $income) {
            return $income->amount;
        });
        $table->addColumn('number', function (Income $income) {
            return $income->invoice_no ? $income->invoice_no : '';
        });
        $table->addColumn('avg_amount', function (Income $income) {
            return $income->incomeClient->getAverageInvoice();
        });
        $table->addColumn('avg_class', function (Income $income) {
            return $income->incomeClient->getAverageInvoice() > $income->amount ? "danger" : "success";
        });

        return $this->calculateTotal($table->make(true));
    }

    public function calculateTotal(JsonResponse $table)
    {
        $data = $table->getData();
        $total = 0;
        foreach ($data->data as $row){
            $total += $row->amount;
        }
        $data->total_sum = Label::formatPrice($total);

        $table->setData($data);
        return $table;
    }
}
