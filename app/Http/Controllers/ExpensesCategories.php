<?php

namespace App\Http\Controllers;

use App\BankAccount;
use App\ExpensesCategory;

class ExpensesCategories extends Controller
{
    public function index()
    {
        $categories = ExpensesCategory::all();

        return view('expenses-categories.index', compact('categories'));
    }

    public function create()
    {
        $categories = ExpensesCategory::all();

        return view('expenses-categories.create', get_defined_vars());
    }

    public function edit(ExpensesCategory $expenses_category)
    {
        $bankAccounts = $expenses_category->accounts;
        $categories = ExpensesCategory::all();

        return view('expenses-categories.edit', get_defined_vars());
    }

    public function store(\App\Http\Requests\ExpensesCategory $request)
    {
        ExpensesCategory::create($request->all());

        return redirect()->route('expenses-categories.index')->with('success', 'New category created');
    }

    public function update(\App\Http\Requests\ExpensesCategory $request, ExpensesCategory $expenses_category)
    {
        $iban = $request->get('iban');
        $expenses_category->update([
            'name' => $request->get('name'),
            'parent_id' => $request->get('parent_id'),
        ]);
        if ($iban) {
            BankAccount::create([
                'exp_cat_id' => $expenses_category->id,
                'iban' => $iban
            ]);
        }

        return redirect()->route('expenses-categories.index')->with('success', 'Category Updated');
    }
}
