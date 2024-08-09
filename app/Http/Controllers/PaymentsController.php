<?php

namespace App\Http\Controllers;

use App\Income;

class PaymentsController extends Controller
{
    public function index()
    {
        $upcoming = Income::where('status', 0)->get();
        $incomes = Income::activeInvoices()->get();
        $totalIncomeToCome = $incomes->sum(fn(Income $income) => $income->unpaid());

        return view('payments.index', get_defined_vars());
    }
}
