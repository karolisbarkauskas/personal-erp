<?php

namespace App\Http\Livewire;

use App\TaskList;
use Livewire\Component;

class AddToWeek extends Component
{
    /** @var TaskList */
    public $week;
    public $employee;
    public $hours;
    protected $listeners = ['refresh' => '$refresh'];
    public $isVisible = false;

    public function toggleVisibility()
    {
        $this->isVisible = !$this->isVisible;
    }

    public function mount($employee, $week)
    {
        $this->week = $week;
        $this->employee = $employee;
    }

    public function updatedHours()
    {
        $this->week->times()->wherePivot('employee_id', $this->employee->id)->update([
            'hours' => $this->hours
        ]);
        $this->isVisible = false;
    }

    public function addToWeek()
    {
        $this->week->tasks()->create([
            'employee_id' => $this->employee->id
        ]);

        $this->emitTo(WeekComponent::class, 'refresh', $this->employee, $this->week);
    }

    public function render()
    {
        return view('livewire.add-to-week');
    }
}
