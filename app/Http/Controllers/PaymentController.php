<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Income;
use App\Payment;

class PaymentController extends Controller
{
    public function store(PaymentRequest $request, Income $income)
    {
        $income->payment()->create($request->all());
        $income->touch();

        if ($income->isPaid()) {
            $income->markAsPaid();
        }

        activity('income')
            ->performedOn($income)
            ->withProperties($request->only([
                'amount',
                'comment'
            ]))
            ->log('Payment assigned');

        return redirect()->route('income.edit', $income)->with('success', 'Payment assigned');
    }

    public function destroy(Income $income, Payment $payment)
    {
        activity('income')
            ->performedOn($income)
            ->withProperties($payment)
            ->log('Payment deleted');

        $payment->delete();
        $income->touch();

        return redirect()->route('income.edit', $income)->with('success', 'Payment DELETED');
    }
}
