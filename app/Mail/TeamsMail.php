<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TeamsMail extends Mailable
{
    use Queueable, SerializesModels;

    private $planned;
    private $debtors;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($planned, $debtors)
    {
        $this->planned = $planned;
        $this->debtors = $debtors;
        $this->subject('Information about our clients');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.clients', [
            'planned' => $this->planned,
            'debtors' => $this->debtors
        ]);
    }
}
