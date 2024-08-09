<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

/**
 * @property float $rate
 */
class Client extends Model
{
    use Notifiable;

    protected $fillable = [
        'name',
        'project',
        'code',
        'vat_code',
        'address',
        'payment_delay',
        'rate',
        'credit',
        'hourly_diff',
        'comment',
        'hourly_diff_reset',
    ];

    public function getTotalIncomeThisYear()
    {
        $incomes = Income::where('client', $this->id)->hasInvoice()->whereBetween('income_date', [
            Carbon::now()->startOfYear(),
            Carbon::now()->endOfYear(),
        ])->get();
        return $incomes->sum(function (Income $income) {
            return $income->amount;
        });
    }

    public function unpaidIncomes()
    {
        return $this
            ->hasMany(Income::class, 'client', 'id')
            ->hasInvoice()
            ->where('status', Income::DEBT);
    }

    public function scopeHasInvoice(Builder $builder)
    {
        return $builder->where('invoice_no', 'like', 'PS%');
    }

    public function getTotalIncome()
    {
        return Income::where('client', $this->id)->hasInvoice()->sum('amount');
    }

    public function getInvoiceCount()
    {
        return Income::where('client', $this->id)->hasInvoice()->get()->count();
    }

    public function incomes()
    {
        return $this->hasMany(Income::class, 'client', 'id');
    }

    public function stats()
    {
        return $this->hasMany(ClientStat::class);
    }

    public function getTotalDebt()
    {
        return Income::where('client', $this->id)->where('invoice_no', 'like', 'PS%')
            ->where('status', Income::DEBT)->get()->sum('amount');

        $income = Income::where('client', $this->id)
            ->where('status', '!=', Income::PLANNED)
            ->get()->sum('total');

        $incomes = Income::where('client', $this->id)
            ->select('id')
            ->where('status', '!=', Income::PLANNED)
            ->get()->toArray();
        $payments = Payment::whereIn('income_id', $incomes)->get()->sum('amount');
        return $payments - $income;
    }

    public function getLastInvoices(int $amount)
    {
        return Income::where('client', $this->id)->orderBy('issue_date', 'desc')->limit($amount)->get();
    }

    public function getAverageInvoice()
    {
        $incomes = Income::where('client', $this->id)->hasInvoice()->get();
        $incomeCount = $incomes->count();
        if ($incomeCount) {
            return round($this->getTotalIncome() / $incomeCount);
        }
        return 0;
    }

    public function getPriority()
    {
        return $this->priority;
    }

    public function getAverageTimeForPeriod($to, $from = '2020-01-01 00:00:00')
    {
        if (!$this->rate) {
            $this->update(['rate' => 30]);
        }
        $times = Time::where('time_records.is_trashed', false)
            ->select(['time_records.value', 'time_records.user_id', 'time_records.created_on'])
            ->join('tasks', 'time_records.parent_id', 'tasks.id')
            ->join('projects', 'tasks.project_id', 'projects.id')
            ->where('time_records.parent_type', 'Task')
            ->where('projects.company_id', $this->id)
            ->whereBetween('time_records.created_on', [$from, $to])
            ->get();
        $timeSpend = 0;
        foreach ($times as $time) {
            $rate = CollabUsers::getRate($time['user_id'], $time['created_on']);
            $timeSpend += $time['value'] * ($rate / $this->rate);
        }

        return $timeSpend;
    }

    public function getAverageTime()
    {
        $timeSpend = $this->getAverageTimeForPeriod(Carbon::now()->format('Y-m-d h:i:s'));
        $created_on = Carbon::createFromTimeString($this->created_on);
        $startDate = Carbon::createFromTimeString('2020-01-01 00:00:00');
        if ($created_on->lt($startDate)) {
            $created_on = $startDate;
        }
        $start = Carbon::parse($created_on)->floorMonth();
        $months = $start->diffInMonths(Carbon::now()->floorMonth());
        if ($months == 0) {
            $months = 1;
        }
        return round($timeSpend / $months, 2);
    }

    public function collabs()
    {
        return $this->hasMany(CollabMaps::class, 'client_id');
    }

    public function contacts()
    {
        return $this->hasMany(ClientContacts::class, 'client_id');
    }

    public function contract()
    {
        return $this->hasOne(Contract::class, 'client_id', 'id');
    }

    public function getCategoryName()
    {
        switch ($this->category){
            case Income::ONESOFT:
                return 'OneSoft';
            case Income::PRESTAPRO:
                return 'PrestaPro';
            default:
                return  '---';
        }
    }

    public function getCriticalTasks()
    {
        $now = now()->subHours(3);
        $last15 = now()->subHours(3)->subMinutes(15);

        return $this->collabs->map(function (CollabMaps $collab) use ($last15, $now) {
            if ($collab->onesoft_collab_id) {
                return \App\Onesoft\Task::where('project_id', $collab->onesoft_collab_id)
                    ->whereBetween('created_on', [
                        $last15,
                        $now
                    ])
                    ->where('is_important', true)
                    ->whereNull('completed_on')
                    ->get();
            }

            if ($collab->prestapro_collab_id) {
                return \App\Prestapro\Task::where('project_id', $collab->prestapro_collab_id)
                    ->whereBetween('created_on', [
                        $last15,
                        $now
                    ])
                    ->where('is_important', true)
                    ->whereNull('completed_on')
                    ->get();
            }
        });
    }

    public function recalculateHourlyDiff()
    {
        $this->update([
            'hourly_diff' => WeekTask::where('client_id', $this->id)->sum('diff') - $this->hourly_diff_reset
        ]);
    }

}
