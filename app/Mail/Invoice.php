<?php

namespace App\Mail;

use App\Income;
use App\IncomeEmails;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Invoice extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var IncomeEmails
     */
    private $email;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(IncomeEmails $email, $language)
    {
        $this->subject($email->subject);
        $this->email = $email;
        $this->bcc('invoicing@invoyer.com');
        $this->from('invoicing@invoyer.com', 'invoyer invoicing');
        $this->replyTo('invoicing@invoyer.com', 'invoyer invoicing');

        foreach (json_decode($email->receivers) as $key => $receiver) {
            if ($key === 0) {
                $this->to($receiver->email, $receiver->full_name);
            } else {
                $this->cc($receiver->email, $receiver->full_name);
            }
        }

        $this->attachFromStorageDisk('invoices',
            $this->email->income->invoice_no . '.pdf',
            $this->email->income->invoice_no . '.pdf'
        );

        if ($email->attach_debts) {
            /** @var Income $income */
            foreach ($email->income->incomeClient->unpaidIncomes as $income) {
                $income->generateInvoice($language)
                    ->filename($income->invoice_no)
                    ->save('invoices');

                $this->attachFromStorageDisk('invoices',
                    $income->invoice_no . '.pdf',
                    'UNPAID ' . $income->invoice_no . '.pdf'
                );
            }
        }

        if ($email->attach_report) {
            $this->attachFromStorageDisk('reports',
                'report.pdf',
                'report.pdf'
            );
        }

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
                'content' => $this->email->content,
                'brandLink' => $this->email->getBrandLink(),
                'brand' => $this->email->getBrandName(),
                'brandLogo' => $this->email->getbrandLogo()
            ]);
    }
}
