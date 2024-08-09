<?php

namespace App\Services;

use App\CollabUsers;
use App\RecurringExpense;
use App\Time;
use Illuminate\Support\Collection;

class Finances
{
    private $nonSalaryExpenses;
    /**
     * @var float
     */
    private $salaryExpenses;
    /**
     * @var int
     */
    private $workDaysPerMonth;
    private $onesoftTeam;
    private $prestaTeam;
    private $interTeam;

    public function __construct($onesoft, $presta, $inter)
    {
        $this->nonSalaryExpenses = RecurringExpense::getCurrentExpenses();
        $this->salaryExpenses = CollabUsers::getEmployeesCost(
            now()->format('m'),
            now()->format('Y')
        );
        $this->workDaysPerMonth = Time::getWorkdays(
            now()->format('Y'),
            now()->format('m')
        );
        $this->onesoftTeam = $onesoft;
        $this->prestaTeam = $presta;
        $this->interTeam = $inter;
    }

    public function getONESOFTMinimum()
    {
        return $this->getMinimum($this->onesoftTeam);
    }

    private function getMinimum(Collection $team)
    {
        return $team->sum('expected_work_time') * $this->getWorkDaysPerMonth() * $this->getCompanyHourlyRateWithoutMarkup();
    }

    /**
     * @return int
     */
    public function getWorkDaysPerMonth(): int
    {
        return $this->workDaysPerMonth;
    }

    public function getCompanyHourlyRateWithoutMarkup(): float
    {
        return $this->getTotalExpenses() / $this->getTotalWorkHoursPerMonth();
    }

    public function getTotalExpenses()
    {
        return $this->getSalaryExpenses() + $this->getNonSalaryExpenses();
    }

    /**
     * @return float
     */
    public function getSalaryExpenses(): float
    {
        return $this->salaryExpenses;
    }

    /**
     * @return mixed
     */
    public function getNonSalaryExpenses(): float
    {
        return $this->nonSalaryExpenses;
    }

    public function getTotalWorkHoursPerMonth()
    {
        return $this->getWorkDaysPerMonth() * $this->getTotalWorkHoursPerDay();
    }

    private function getTotalWorkHoursPerDay()
    {
        return array_sum([
                $this->onesoftTeam->sum('expected_work_time'),
                $this->prestaTeam->sum('expected_work_time')
            ]
        );
    }

    public function getONESOFTPreferred()
    {
        return $this->getPreferred($this->onesoftTeam);
    }

    private function getPreferred(Collection $team)
    {
        return $team->sum('expected_work_time') * $this->getWorkDaysPerMonth() * $this->getCompanyHourlyRateWithMarkup();
    }

    public function getCompanyHourlyRateWithMarkup()
    {
        return (1 + ($this->getMarkup() / 100)) * $this->getCompanyHourlyRateWithoutMarkup();
    }

    public function getMarkup()
    {
        return 40;
    }

    public function getPrestaPROMinimum()
    {
        return $this->getMinimum($this->prestaTeam);
    }

    public function getPrestaPROPrefered()
    {
        return $this->getPreferred($this->prestaTeam);
    }

    public function getInterToGenerate()
    {
        return $this->interTeam->sum(function (CollabUsers $user) {
            return $user->getSalary(
                now()->format('Y'),
                now()->format('m')
            );
        });
    }

}
