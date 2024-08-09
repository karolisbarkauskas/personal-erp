<?php

namespace App\Http\Livewire;

use App\Employee;
use App\RecurringExpense;
use App\Settings;
use Livewire\Component;

class ReportDemo extends Component
{

    public $salaries;
    public $expenses;
    public $averageMarkup;
    public $hourlyRate;
    public $minimalHoursToSellNoProfit;
    public $markup;
    public $optimalHoursToSell;
    public $healthyHoursToSell;
    public $recurringExpenses;
    public $employees;
    public $allExpenses;
    public $allSalaries;

    public function mount()
    {
        $this->averageMarkup = ceil(Employee::active()->get()->average('markup'));
        $this->hourlyRate = Settings::getHourlyRate();
        $this->minimalHoursToSellNoProfit = Settings::getSalesHours();
        $this->markup = Settings::getMarkup();
        $this->optimalHoursToSell = Settings::getSalesHours(Settings::getMarkup());
        $this->healthyHoursToSell = Settings::getSalesHours($this->averageMarkup);

        $this->salaries = Employee::active()->get()->sum('salary_with_vat');
        $this->recurringExpenses = RecurringExpense::where('installment', 0)->where('size', '!=', 0)->get();
        $this->employees = Employee::active()->get();
        $this->expenses = $this->recurringExpenses->sum('size') + $this->employees->sum('salary_to_cover');

        $i = 0;
        foreach ($this->employees as $employee) {
            $i++;
            $this->allSalaries[$i] = $employee->salary_with_vat;
        }
        foreach ($this->employees as $employee) {
            $i++;
            $this->allExpenses[$i] = $employee->salary_to_cover;
        }
        foreach ($this->recurringExpenses as $recurringExpense) {
            $i++;
            $this->allExpenses[$i] = $recurringExpense->size;
        }
    }

    public function getSalesHours($profit = 0): float
    {
        $salaries = $this->salaries;
        $expenses = $this->expenses;
        $hourlyRate = $this->hourlyRate;
        $total = $expenses + $salaries;

        if ($profit != 0) {
            $total = ($expenses + $salaries) * (1 + ($profit / 100));
        }

        if ($hourlyRate <= 0) {
            return 0;
        }

        return $total / $hourlyRate;
    }

    public function render()
    {
        return view('livewire.report-demo');
    }

    public function calculate()
    {
        $this->expenses = array_sum($this->allExpenses);
        $this->salaries = array_sum($this->allSalaries);
        $this->minimalHoursToSellNoProfit = $this->getSalesHours();
        $this->optimalHoursToSell = $this->getSalesHours($this->markup);
        $this->healthyHoursToSell = $this->getSalesHours($this->averageMarkup);
    }

}
