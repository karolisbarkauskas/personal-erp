<?php

namespace App\Http\Livewire;

use App\SaleEstimate;
use Livewire\Component;

class SaleResourcePlannerRow extends Component
{
    /** @var SaleEstimate */
    public $estimate;
    public $hourly_rate;
    public $hours_needed;
    public $total;
    public $hours_for_employee;
    public $remarks;

    public function mount(SaleEstimate $estimate)
    {
        $this->estimate = $estimate;
        $this->hourly_rate = $estimate->hourly_rate;
        $this->hours_needed = $estimate->hours_needed;
        $this->total = $estimate->total;
        $this->hours_for_employee = $estimate->hours_for_employee;
        $this->remarks = $estimate->remarks;
    }

    public function updatedHourlyRate()
    {
        $this->validate([
            'hours_needed' => 'numeric|required',
            'hourly_rate' => 'numeric|required',
        ]);

        $this->calculateAndSave();
    }

    public function updatedHoursNeeded()
    {
        $this->validate([
            'hours_needed' => 'numeric|required',
            'hourly_rate' => 'numeric|required',
        ]);

        $this->calculateAndSave();
    }

    private function calculateAndSave()
    {
        $this->total = $this->hours_needed * $this->hourly_rate;

        $this->hours_for_employee = round(($this->hourly_rate * $this->hours_needed) / $this->estimate->empl->hourly_rate_with_markup, 2);

        $this->estimate->update([
            'remarks' => $this->remarks,
            'hourly_rate' => $this->hourly_rate,
            'hours_needed' => $this->hours_needed,
            'total' => $this->total,
            'hours_for_employee' => $this->hours_for_employee,
        ]);
        $this->emitTo(SaleResourcePlanner::class, 'refreshComponent');
    }

    public function updatedRemarks()
    {
        $this->calculateAndSave();
    }

    public function render()
    {
        return view('livewire.sale-resource-planner-row');
    }
}
