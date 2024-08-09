<?php

namespace App\Http\Controllers;

use App\CollabUsers;

class ProjectTimeCalculator extends Controller
{
    public function index()
    {
        $employees = CollabUsers::active()->get();

        $total = $this->calculateTotal();

        return view('calculator.index', compact('employees', 'total'));
    }

    private function calculateTotal(): float
    {
        $total = 0;
        if (request()->get('calculator')) {
            $results = request()->get('emp');

            foreach ($results as $result) {
                $total += (float) $result['time'] * (float) $result['rate'];
            }

            $total *= 1 + ((float) request()->get('buffer', 0) / 100);
        }

        return round($total);
    }
}
