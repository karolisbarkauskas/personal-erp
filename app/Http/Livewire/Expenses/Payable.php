<?php

namespace App\Http\Livewire\Expenses;

use App\Http\Livewire\Expenses\Upcoming\ListComponent;
use Livewire\Component;

class Payable extends Component
{
    public \App\Payable $payable;

    protected $rules = [
        'payable.amount_paid' => 'nullable',
        'payable.amount' => 'nullable',
    ];

    public function save()
    {
        $this->payable->save();
        $this->emitTo(ListComponent::class, 'refreshComponent');
    }

    public function mount(\App\Payable $payable)
    {
        $this->payable = $payable;
    }

    public function destroy()
    {
        $this->payable->delete();
        $this->emitTo(ListComponent::class, 'refreshComponent');
    }

    public function render()
    {
        return view('livewire.expenses.upcoming-payments');
    }
}
