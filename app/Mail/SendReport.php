<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendReport extends Mailable
{
    use Queueable, SerializesModels;

    private $report;

    /**
     * Create a new message instance.
     *
     * @param $report
     */
    public function __construct($report)
    {
        $this->report = $report;
        $this->subject('There are tasks that needs information or QA!');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.reminders', [
            'report' => $this->report
        ]);
    }
}
