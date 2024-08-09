<?php

namespace App\Http\Controllers;

use App\CollabUsers;
use App\IncomeCategory;
use App\IncomeCost;
use Carbon\Carbon;
use Illuminate\Http\Request;

class IncomeCostController extends Controller
{


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $from = $request->get('from');
        $data = [
            'from' => $from,
            'id_income_categories' => $request->get('income_category')
        ];
        if($employeeId = $request->get('user_id', false)){
            if (!$from){
                $user = CollabUsers::where('id', $employeeId)->first();
                $from = $user->created_on;
            }
            $data['user_id'] = $employeeId;
            $data['expense_type'] =  IncomeCost::EMPLOYEE_COST;
            $data['percentage'] = $request->get('percentage');
        }
        if($categoryId = $request->get('category', false)){
            $data['category'] = $categoryId;
            $data['expense_type'] =  IncomeCost::EXPENSES_COST;
            $data['percentage'] = $request->get('cat_percentage');
        }
        IncomeCost::create($data);

        return redirect()->back()->with('success', 'Cost added');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\IncomeCost $incomeCost
     * @return \Illuminate\Http\Response
     */
    public function destroy(IncomeCost $incomecost)
    {
        $incomecost->to = Carbon::now();
        $incomecost->save();
        return redirect()->back()->with('success', 'Income cost removed');
    }
}
