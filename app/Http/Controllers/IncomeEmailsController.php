<?php

namespace App\Http\Controllers;

use App\ClientContacts;
use App\Http\Requests\SendEmailRequest;
use App\Income;
use App\IncomeEmails;
use App\Status;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IncomeEmailsController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(SendEmailRequest $request)
    {
        $recipients = ClientContacts::whereIn('id', $request->get('sendto'))
            ->select('email', 'full_name')
            ->get()
            ->toArray();

        /** @var IncomeEmails $email */
        $email = IncomeEmails::create([
            'user_id' => auth()->user()->id,
            'income_id' => $request->get('income'),
            'subject' => $request->get('subject'),
            'attach_debts' => $request->get('attach-debts', false),
            'attach_report' => $request->get('attach-report', false),
            'receivers' => json_encode($recipients),
            'content' => $request->get('content'),
            'locale' => $request->get('language'),
            'send_at' => !$request->has('no_scheduling') ? $request->get('schedule_time') : null,
        ]);

        $email->income->update([
            'status' => Status::SENT
        ]);

        if ($request->has('no_scheduling')) {
            $email->income->sendViaEmail($email, $email->locale);

            return redirect()->route('income.edit', $email->income)->with('success', 'SENT');
        }

        return redirect()->route('income.edit', $email->income)->with('success', 'Email scheduled');
    }

    /**
     * @param Income $income
     * @return \Illuminate\Contracts\Foundation\Application|Factory|View
     */
    public function create(Request $request)
    {
        $income = Income::find($request->get('income'));

        return view('income-emails.create', compact('income'));
    }
}
