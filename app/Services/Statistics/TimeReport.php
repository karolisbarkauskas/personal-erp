<?php

namespace App\Services\Statistics;

use App\TaskList;
use Illuminate\Database\Eloquent\Builder;

class TimeReport
{

    public $yearFrom;
    public $weekFrom;
    public $yearTo;
    public $weekTo;

    public $totalMinimal;
    public $totalCurrent;
    public $totalOptimal;

    public array $report;
    /**
     * @var mixed
     */
    public $normalizedValues;
    public $averages;
    public $count = 0;
    private bool $minimalNeeded;

    public function __construct(bool $minimalNeeded = false)
    {
        $this->minimalNeeded = $minimalNeeded;
    }

    /**
     * @param mixed $yearFrom
     * @return TimeReport
     */
    public function setYearFrom($yearFrom)
    {
        $this->yearFrom = $yearFrom;
        return $this;
    }

    /**
     * @param mixed $weekFrom
     * @return TimeReport
     */
    public function setWeekFrom($weekFrom)
    {
        $this->weekFrom = $weekFrom;
        return $this;
    }

    /**
     * @param mixed $yearTo
     * @return TimeReport
     */
    public function setYearTo($yearTo)
    {
        $this->yearTo = $yearTo;
        return $this;
    }

    /**
     * @param mixed $weekTo
     * @return TimeReport
     */
    public function setWeekTo($weekTo)
    {
        $this->weekTo = $weekTo;
        return $this;
    }

    public function report()
    {
        $this->report = $this->getTasks()->get()->map(function ($task) {
            if ($task->client_sold_hours > 0 && $task->week <= $this->weekTo && (
                    $task->week <= now()->weekOfYear && $task->year == now()->year
                )) {
                $this->totalMinimal += $this->minimalNeeded ? $task->break_even_hours : $task->client_sellable_hours;
                $this->totalCurrent += $task->client_sold_hours;
                $this->totalOptimal += $task->optimal_sellable_hours;
                $this->count++;
            }

            return [
                'week' => $task->year . '-' . $task->week,
                'minimal' => $task->client_sellable_hours,
                'current' => $task->client_sold_hours,
                'optimal' => $task->optimal_sellable_hours,
                'survival' => $task->break_even_hours,
            ];
        })->toArray();

        $this->averages['soldHours'] = round($this->totalCurrent / ($this->count > 0 ? $this->count : 1));
        $this->averages['totalMinimal'] = round($this->totalMinimal / ($this->count > 0 ? $this->count : 1));

        return $this;
    }

    /**
     * @return Builder
     */
    public function getTasks(): Builder
    {
        return TaskList::query()
            ->where('year', '>=', $this->yearFrom)
            ->where('week', '>=', $this->weekFrom)
            ->where('year', '<=', $this->yearTo)
            ->where('week', '<=', $this->weekTo)
            ->orderBy('year')
            ->orderBy('week');
    }

    public function normalizeValues()
    {
        $range = $this->totalOptimal;
        if ($range == 0) {
            $this->normalizedValues['totalMinimal'] = 0;
            $this->normalizedValues['totalCurrent'] = 0;
            $this->normalizedValues['totalOptimal'] = 0;
            return;
        }

        $this->normalizedValues['totalMinimal'] = round(($this->totalMinimal) / $range * 100);
        $this->normalizedValues['totalCurrent'] = round(($this->totalCurrent) / $range * 100);
        $this->normalizedValues['totalOptimal'] = round(($this->totalOptimal) / $range * 100);
    }

}
