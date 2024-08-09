<?php

namespace App\Http\Controllers;

use App\CollabUsers;
use App\EmployeeRateHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EmployeeRateHistoryController extends Controller
{


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {

        $collabUserId = $request->get('employee_id');

        if (!$collabUserId){
            return redirect()->back()->with('error', 'Employee Id not found');
        }

        $from = $request->get('from', Carbon::now()->format('Y-m-d'));
        EmployeeRateHistory::create([
            'collab_user' => $collabUserId,
            'rate' =>  $request->get('rate'),
            'salary_bruto' =>  $request->get('salary_bruto'),
            'from' => $from
        ]);
        return redirect()->back()->with('success', 'Salary/Rate added');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  EmployeeRateHistory $rateHistory
     * @return string
     */
    public function edit(EmployeeRateHistory $rateHistory)
    {
        return response()->json(view('rateHistory.index', compact(
            'rateHistory'
        ))->render());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  EmployeeRateHistory $rateHistory
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, EmployeeRateHistory $rateHistory)
    {
        $rateHistory->update($this->prepareVariables($request));
        return redirect()->back()->with('success', 'Salary/Rate updated');
    }

    private function prepareVariables(Request $request){
        return [
            'rate' => $request->get('rate'),
            'salary_bruto'=>  $request->get('salary_bruto'),
            'from' => $request->get('from'),
        ];
    }
}
