<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Tracker extends Mailable
{
    use Queueable, SerializesModels;

    private $employee;

    /**
     * Create a new message instance.
     *
     * @param $employee
     */
    public function __construct($employee)
    {
        $this->subject("Nudge reminder {$employee->getFullName()}");
        $this->employee = $employee;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.time', [
            'employee' => $this->employee
        ]);
    }
}
