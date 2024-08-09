<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReportSend extends Mailable
{
    use Queueable, SerializesModels;

    /** @var \App\Reports\Productivity */
    private $report;

    /**
     * Create a new message instance.
     *
     * @param $report
     */
    public function __construct($report)
    {
        $this->report = $report;
        $this->subject('Weekly performance report');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.report', [
            'timeThisMonth' => $this->report->getTimeBookedThisMonth(),
            'timeThisWeek' => $this->report->getTimeBookedThisWeek(),
            'offDaysInWeek' => $this->report->getDaysOffWithinWeek(),
            'offDaysInMonth' => $this->report->getDaysOffWithinMonth(),
        ]);
    }
}
