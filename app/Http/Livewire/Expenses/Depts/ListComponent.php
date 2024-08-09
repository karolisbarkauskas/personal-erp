<?php

namespace App\Http\Livewire\Expenses\Depts;

use App\RecurringExpense;
use Livewire\Component;

class ListComponent extends Component
{
    public function render()
    {
        $depts = RecurringExpense::where('installment', '>', 0)->get();

        return view('livewire.expenses.depts.list-component', compact('depts'));
    }
}
