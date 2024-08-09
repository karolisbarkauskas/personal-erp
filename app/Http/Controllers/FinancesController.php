<?php

namespace App\Http\Controllers;

use App\CollabUsers;
use App\RecurringExpense;
use App\Services\Finances;

class FinancesController extends Controller
{
    public function index()
    {
        $onesoftTeam = CollabUsers::ONESOFT()->get();
        $prestaPROTeam = CollabUsers::PrestaPRO()->get();
        $interTeam = CollabUsers::Inter()->get();
        $recurringExpenses = RecurringExpense::active()->orderBy('size', 'desc')->get();

        $finances = new Finances($onesoftTeam, $prestaPROTeam, $interTeam);

        return view('finances.index', get_defined_vars());
    }
}
