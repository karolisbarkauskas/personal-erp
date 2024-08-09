<?php

namespace App\Http\Controllers;

use App\ExpensesCategory;
use App\RecurringExpense;

class RecurringExpenses extends Controller
{
    public function index()
    {
        $expenses = RecurringExpense::all();

        return view('recurring-expenses.index', compact('expenses'));
    }

    public function create()
    {
        $categories = ExpensesCategory::all();

        return view('recurring-expenses.create', compact('categories'));
    }

    public function store(\App\Http\Requests\RecurringExpenses $request)
    {
        RecurringExpense::create($request->validated());

        return redirect()->route('recurring-expenses.index')->with('success', 'New expense created');
    }

    public function edit(RecurringExpense $recurring_expense)
    {
        $categories = ExpensesCategory::all();
        $recurringExpensesList = $recurring_expense->getRecurringExpensesList();
        $lastPaymentSize = $recurring_expense->size;
        if (count($recurringExpensesList) > 0) {
            $lastPaymentSize = end($recurringExpensesList)['size'];
        }
        $expenses = RecurringExpense::all();

        return view('recurring-expenses.edit', get_defined_vars());
    }

    public function update(\App\Http\Requests\RecurringExpenses $request, RecurringExpense $recurring_expense)
    {
        $recurring_expense->update($request->validated());

        return redirect()->route('recurring-expenses.index')->with('success', 'Expense edited');
    }
}
