<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'value'
    ];
    public const WORK_DAYS_PER_MONTH = 21;
    public const WEEKS_PER_MONTH_AVG = 4.348125;

    public static function getMarkup(): float
    {
        return self::query()->where('name', 'MARKUP')->value('value');
    }

    public static function getBankBalance()
    {
        return self::query()->where('name', 'BANK_TOTAL')->value('value');
    }

    public static function getHourlyRate()
    {
        return self::query()->where('name', 'SELLABLE_HOUR')->value('value');
    }

    public static function getVatToPay()
    {
        return self::query()->where('name', 'VAT_TO_PAY')->value('value');
    }

    public static function getPaidVat()
    {
        return self::query()->where('name', 'PAID_VAT')->value('value');
    }

    public static function setBankBalance($balance)
    {
        return self::query()->where('name', 'BANK_TOTAL')->update([
            'value' => $balance
        ]);
    }

    public static function getSalesHours($profit = 0): float
    {
        $salaries = Employee::active()->get()->sum('salary_with_vat');
        $expenses = Expense::getRunningExpenses();
        $hourlyRate = Settings::getHourlyRate();
        $total = $expenses + $salaries;

        if ($profit > 0) {
            $total = ($expenses + $salaries) * (1 + ($profit / 100));
        }

        return $total / $hourlyRate;
    }

    public static function calculatePayableVat()
    {
        $currentVatToPay = Settings::where('name', 'VAT_TO_PAY')->first();
        $totalVatToPay = Income::where('vat_amount', '>', 0)->sum('vat_amount');
        $vatToDeduce = Expense::where('vat_size', '>', 0)->get()->map(function (Expense $expense) {
            return $expense->size * (1 + ($expense->vat_size / 100)) - $expense->size;
        })->sum();

        $currentVatToPay->update([
            'value' => $totalVatToPay - $vatToDeduce - Settings::getPaidVat()
        ]);
    }

}
