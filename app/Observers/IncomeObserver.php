<?php

namespace App\Observers;

use App\CashFlow;
use App\Income;
use App\Status;
use Carbon\Carbon;

class IncomeObserver
{
    /**
     * Handle the income "created" event.
     *
     * @param \App\Income $income
     *
     * @return void
     */
    public function creating(Income $income)
    {
        $this->setTotals($income);
    }

    /**
     * @param \App\Income $income
     */
    private function setTotals(Income $income): void
    {
        $income->vat_amount = round($income->amount * (1 + ($income->vat_size / 100)) - $income->amount, 2);
        $income->total = $income->vat_amount + $income->amount;
    }

    /**
     * Handle the income "updated" event.
     *
     * @param \App\Income $income
     *
     * @return void
     */
    public function updating(Income $income)
    {
        $vatBefore = $income->vat_amount;
        $this->setTotals($income);
        $vatAfter = $income->vat_amount;

        $date = Carbon::createFromFormat('Y-m-d', $income->issue_date);

        if ($date->isLastMonth()) {
            CashFlow::whereType(CashFlow::VAT)
                ->latest()
                ->first()
                ->decrement('real', $vatBefore);

            CashFlow::whereType(CashFlow::VAT)
                ->latest()
                ->first()
                ->increment('real', $vatAfter);
        }

        $income->cashFlow()->update([
            'initial' => $income->amount,
            'real' => $income->getUnpaidMoney(),
            'paid' => $income->status == Status::PAID
        ]);
    }
}
