<?php

namespace App\Mail;

use App\Client;
use App\Income;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DebtorEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Client
     */
    private $client;
    /** @var Income mixed */
    private $income;
    /**
     * @var false
     */
    private $dry;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Client $client, $dry = false)
    {
        $this->client = $client;

        $this->subject(
            ($dry ? 'DEMO: ' : '') . 'Priminimas dėl neapmokėtos sąskaitos įmonei ' . $this->client->name
        );

        foreach ($client->unpaidIncomes as $income) {
            $this->income = $income;

            $income->generateInvoice()
                ->filename($income->invoice_no)
                ->save('invoices');

            $this->attachFromStorageDisk('invoices',
                $income->invoice_no . '.pdf',
                'UNPAID ' . $income->invoice_no . '.pdf'
            );
        }

        $this->from(
            'karolis@barkauskas.net',
            'invoyer invoicing'
        );
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
                'content' => view('invoice.debt', [
                    'invoices' => $this->client->unpaidIncomes->pluck('invoice_no')
                ])->render(),
                'brandLink' => 'https://invoyer.com',
                'brand' => 'invoyer',
                'brandLogo' => 'https://invoicing.invoyer.dev/images/logo.svg',
            ]);
    }
}
