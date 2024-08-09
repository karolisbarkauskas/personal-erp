<?php

namespace App\Http\Controllers;

use App\CollabUsers;
use App\ExpensesCategory;
use App\IncomeCategory;
use App\IncomeCost;
use Carbon\Carbon;

class IncomeCategoriesController extends Controller
{
    public function index()
    {
        $categories = IncomeCategory::all();

        return view('income-categories.index', compact('categories'));
    }

    public function store(\App\Http\Requests\IncomeCategory $request)
    {
        $category = IncomeCategory::create($request->all());

        return redirect()->route('income-categories.edit', $category)->with('success', 'New category created');
    }

    public function create()
    {
        return view('income-categories.create');
    }

    public function edit(IncomeCategory $income_category)
    {
        $assignedEmployees = IncomeCost::where('id_income_categories', $income_category->id)
            ->whereNotNull('user_id')
            ->select('user_id')->pluck('user_id')->toArray();
        $employees = CollabUsers::active()->whereNotIn('id', $assignedEmployees)->get();
        $expenseCategories = ExpensesCategory::all();
        $incomeCosts = IncomeCost::where('id_income_categories', $income_category->id)->whereNull('to')->orWhere('to', '<=', (new Carbon())->now()->endOfMonth())->get();
        return view('income-categories.edit',
            compact(
                'income_category',
                'employees',
                'incomeCosts',
                'expenseCategories'
            )
        );
    }

    public function update(\App\Http\Requests\ExpensesCategory $request, \App\IncomeCategory $income_category)
    {
        $income_category->update($request->all());

        return redirect()->route('income-categories.index')->with('success', 'Category Updated');
    }

    public function destroy(IncomeCategory $income_category)
    {
        $income_category->delete();

        return redirect()->route('income-categories.index')
            ->with('success', 'Category DELETED');
    }
}
