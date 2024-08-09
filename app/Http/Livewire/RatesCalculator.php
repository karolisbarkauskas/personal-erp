<?php

namespace App\Http\Livewire;

use App\Employee;
use App\Settings;
use Livewire\Component;

class RatesCalculator extends Component
{

    public $employees;
    public $hours;
    public $euros;

    protected $rules = [
        'euros' => 'numeric',
        'hours' => 'numeric',
    ];


    public function mount()
    {
        $this->hours = 1;
        $this->euros = $this->hours * Settings::getHourlyRate();
        $this->employees = Employee::active()->get();
    }

    public function updatedHours()
    {
        $this->euros = (float)$this->hours * Settings::getHourlyRate();
    }

    public function updatedEuros()
    {
        $this->hours = (float)$this->euros / Settings::getHourlyRate();
    }

    public function render()
    {
        return view('livewire.rates-calculator');
    }
}
