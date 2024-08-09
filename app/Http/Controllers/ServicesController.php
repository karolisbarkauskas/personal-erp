<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExpensesCategory;
use App\Service;

class ServicesController extends Controller
{

    public function index()
    {
        $services = Service::all();

        return view('services.index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('services.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExpensesCategory $request)
    {
        Service::create($request->all());

        return redirect()->route('service.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Service $service)
    {
        $incomes = \App\RecurringIncome::where('service_id', $service->id)->get();
        $recurringIncome = 0;
        $incomes->each(function ($income) use (&$recurringIncome) {
            $recurringIncome += $income->amount / $income->period;
        });

        return view('services.edit', compact('service', 'recurringIncome'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(ExpensesCategory $request, Service $service)
    {
        $service->update(
            $request->all()
        );

        return redirect()->route('service.index')->with('success', 'updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()->route('service.index')->with('success', 'DELETED');
    }
}
