<?php

namespace App\Http\Controllers;

use App\Client;
use App\Http\Requests\CreateRecurringIncome;
use App\RecurringExpense;
use App\Service;

class RecurringIncome extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $incomes = \App\RecurringIncome::all();

        return view('recurring-income.index', compact('incomes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRecurringIncome $request)
    {
        \App\RecurringIncome::create(
            $request->validated() + [
                'company_id' => auth()->user()->current_company,
                'next_invoice_date' => now()->addDay()
            ]
        );

        return redirect(route('recurring-income.index'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clients = Client::all();
        $services = Service::all();

        return view('recurring-income.create',
            compact('clients', 'services')
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(\App\RecurringIncome $recurringIncome)
    {
        $clients = Client::all();
        $services = Service::all();
        $expenses = RecurringExpense::all();

        return view('recurring-income.edit',
            compact('clients', 'services', 'recurringIncome', 'expenses')
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateRecurringIncome $request, \App\RecurringIncome $recurringIncome)
    {
        $recurringIncome->update(
            $request->validated() + [
                'company_id' => auth()->user()->current_company
            ]
        );

        return redirect(route('recurring-income.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(\App\RecurringIncome $recurringIncome)
    {
        $recurringIncome->delete();

        return redirect(route('recurring-income.index'));
    }
}
