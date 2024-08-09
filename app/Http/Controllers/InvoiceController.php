<?php

namespace App\Http\Controllers;

use App\Income;

class InvoiceController extends Controller
{
    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \Exception
     */
    public function download(Income $income, $language)
    {
        return $income->generateInvoice($language)->stream();
    }

    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \Exception
     */
    public function report(Income $income, $language)
    {
        if ($income->reports->where('include', true)->isEmpty()) {
            return redirect()->back()->with('error', 'No rows in report');
        }
        return $income->generateReport($language)->stream();
    }
}
