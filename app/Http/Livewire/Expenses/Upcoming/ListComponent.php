<?php

namespace App\Http\Livewire\Expenses\Upcoming;

use App\Payable;
use Livewire\Component;

class ListComponent extends Component
{
    protected $listeners = ['refreshComponent' => '$refresh'];

    public $name;
    public $sum;
    public $date;

    protected $rules = [
        'name' => 'required',
        'date' => 'required',
        'sum' => 'required',
    ];

    public function save()
    {
        $this->validate();

        Payable::create([
            'name' => $this->name,
            'amount' => $this->sum,
            'amount_paid' => 0,
            'deadline' => $this->date
        ]);
    }

    public function render()
    {
        $payables = Payable::whereColumn('amount_paid', '<', 'amount')->orderBy('deadline')->get();

        return view('livewire.expenses.upcoming.list-component', compact('payables'));
    }
}
