<?php

namespace App\Mail;

use App\Income;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InformAboutNewIncome extends Mailable
{
    use Queueable, SerializesModels;

    private Income $income;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Income $income)
    {
        $this->income = $income;
        $this->subject("New income line added in report for {$this->income->incomeClient->name}. {$this->income->incomeClient->project}");
        $this->to('karolis@invoyer.com');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->markdown('vendor.mail.html.message', [
                'content' => view('invoice.new-row', [
                    'reports' => $this->income->reports()->get()->reverse(),
                    'client' => $this->income->incomeClient
                ])->render(),
                'brandLink' => 'https://invoyer.com',
                'brand' => 'invoyer',
                'brandLogo' => 'https://invoicing.invoyer.dev/images/logo.svg',
            ]);
    }
}
