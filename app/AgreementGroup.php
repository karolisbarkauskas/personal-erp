<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgreementGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'name',
        'description',
        'start',
        'deadline',
        'budget',
        'cost'
    ];

    public function sale(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Sale::class, 'id', 'sale_id');
    }

    public function agreements(): \Illuminate\Database\Eloquent\Relations\hasMany
    {
        return $this->hasMany(Agreement::class, 'agreement_group_id', 'id');
    }

    public static function getActiveAgreementGroups()
    {
        return AgreementGroup::with('agreements')
            ->has('agreements')
            ->orderBy('deadline', 'asc')
            ->get();
    }

    public function getDeadlineStatus(): string
    {
        $remainingDays = $this->getRemainingDays();
        switch ($remainingDays) {
            case $remainingDays <= 0:
                return "BAD";
            case ($remainingDays > 0 && $remainingDays <= 7):
                return "GREITAI";
            case ($remainingDays > 7 && $remainingDays <= 14):
                return "2 SAVAITES";
            case ($remainingDays > 14):
                return "OK";
        }
        return  "---";
    }

    public function getRemainingDays(): int
    {
        $now = Carbon::now();
        return $now->diffInDays(Carbon::createFromDate($this->deadline), false);
    }

    public function getProgress()
    {
        $allAgreements = $this->agreements->count();
        if (!$allAgreements){
            return 100;
        }
        $compleated = 0;
        foreach ($this->agreements as $agreement){
            if ($agreement->task->completed_on){
                $compleated++;
            }
        }
        return round(($compleated / $allAgreements) * 100, 2);
    }

    public function getBudget()
    {
        if($this->budget){
            return $this->budget;
        }
        $this->budget = 0;
        foreach ($this->agreements as $agreement){
            $this->budget += $agreement->budget;
        }
        return $this->budget;
    }

    public function getBudgetStatus()
    {
        return '.';
    }
}
