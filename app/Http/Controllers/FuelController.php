<?php

namespace App\Http\Controllers;

use App\Fuel;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FuelController extends Controller
{
    public function store(Request $request)
    {
        Fuel::create([
            'user_id' => $request->get('employee'),
            'amount' => $request->get('amount'),
            'date' => Carbon::parse($request->get('date'))->format('Y-m-d'),
            'type' => $request->get('type'),
        ]);

        return redirect()->back()->with('success', 'OK');
    }
}
