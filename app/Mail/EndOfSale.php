<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EndOfSale extends Mailable
{
    use Queueable, SerializesModels;

    private $sale;

    /**
     * Create a new message instance.
     *
     * @param $sale
     */
    public function __construct($sale)
    {
        $this->sale = $sale;
        $this->subject('Sale #' . $this->sale->id . ' closed');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.end-of-sale', [
            'sale' => $this->sale
        ]);
    }
}
