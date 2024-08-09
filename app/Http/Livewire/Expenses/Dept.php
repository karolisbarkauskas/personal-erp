<?php

namespace App\Http\Livewire\Expenses;

use App\RecurringExpense;
use Livewire\Component;

class Dept extends Component
{
    public RecurringExpense $dept;
    public $reduce;

    protected $rules = [
        'dept.amount' => 'nullable',
    ];

    public function mount(RecurringExpense $dept)
    {
        $this->dept = $dept;
    }

    public function save()
    {
        $this->dept->decrement('size', $this->reduce);
    }

    public function render()
    {
        return view('livewire.expenses.dept');
    }
}
