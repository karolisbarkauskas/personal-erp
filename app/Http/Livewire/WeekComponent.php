<?php

namespace App\Http\Livewire;

use Livewire\Component;

class WeekComponent extends Component
{
    protected $listeners = ['refresh' => '$refresh'];

    public $employee;
    public $week;

    public function mount($employee, $week)
    {
        $this->week = $week;
        $this->employee = $employee;
    }

    public function render()
    {
        return view('livewire.week-component');
    }
}
