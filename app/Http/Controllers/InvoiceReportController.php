<?php

namespace App\Http\Controllers;

use App\Expense;
use App\Income;
use App\Services\Report;
use Carbon\Carbon;

class InvoiceReportController extends Controller
{
    public function zip($date)
    {
        $date = Carbon::createFromFormat('Y-m', $date);

        $dateRange = [
            $date->copy()->startOfMonth(),
            $date->copy()->endOfMonth()
        ];
        $incomes = Income::whereBetween(
            'issue_date', $dateRange
        )->hasInvoice()->get();

        $service = new Report($incomes);

        try {
            $service->generateInvoices();
            $service->zipInvoices();
            $service->deleteFolder();

            return $service->download();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
